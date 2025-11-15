<?php

namespace App\Http\Controllers\Gateway\Stripe;

use App\Constants\Status;
use App\Models\Deposit;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Token;
use Illuminate\Support\Facades\Session;


class ProcessController extends Controller
{

    /*
     * Stripe Gateway
     */
    public static function process($deposit)
    {

        $alias = $deposit->gateway->alias;

        $send['track'] = $deposit->trx;
        $send['view'] = 'user.payment.'.$alias;
        $send['method'] = 'post';
        $send['url'] = route('ipn.'.$alias);
        return json_encode($send);
    }

    public function ipn(Request $request)
    {
        $track = Session::get('Track');
        $deposit = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();
        
        if (!$deposit) {
            $notify[] = ['error', 'Deposit record not found.'];
            return back()->withNotify($notify);
        }
        
        if ($deposit->status == Status::PAYMENT_SUCCESS) {
            $notify[] = ['error', 'This payment has already been processed.'];
            return redirect($deposit->success_url)->withNotify($notify);
        }
        
        // Validate card details
        $request->validate([
            'cardNumber' => 'required',
            'cardExpiry' => 'required',
            'cardCVC' => 'required',
            'name' => 'required',
        ]);

        $cc = $request->cardNumber;
        $exp = $request->cardExpiry;
        $cvc = $request->cardCVC;

        $exp = explode("/", $exp);
        if (!@$exp[1]) {
            $notify[] = ['error', 'Invalid expiry date provided'];
            return back()->withNotify($notify);
        }
        $emo = trim($exp[0]);
        $eyr = trim($exp[1]);
        
        // Ensure year is 4 digits
        if (strlen($eyr) == 2) {
            $eyr = '20' . $eyr;
        }

        $stripeAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);

        Stripe::setApiKey($stripeAcc->secret_key);
        Stripe::setApiVersion("2023-10-16");

        // Clean card number
        $cleanCardNumber = str_replace(' ', '', $cc);
        
        // Check if using test card numbers - use test tokens instead (bypasses raw card data requirement)
        $testCardTokens = [
            '4242424242424242' => 'tok_visa',           // Visa - Success
            '5555555555554444' => 'tok_mastercard',     // Mastercard - Success
            '378282246310005'  => 'tok_amex',           // Amex - Success
            '6011111111111117' => 'tok_discover',       // Discover - Success
            '4000000000000002' => 'tok_chargeDeclined', // Declined
            '4000000000009995' => 'tok_chargeDeclinedInsufficientFunds', // Insufficient funds
        ];
        
        // If test card detected, use pre-generated test token
        if (isset($testCardTokens[$cleanCardNumber])) {
            $stripeToken = $testCardTokens[$cleanCardNumber];
        } else {
            // Not a test card - try to create token (requires raw card data API)
            try {
                $tokenObj = Token::create(array(
                    "card" => array(
                        "number" => $cleanCardNumber,
                    "exp_month" => $emo,
                    "exp_year" => $eyr,
                    "cvc" => "$cvc"
                )
            ));
                $stripeToken = $tokenObj['id'];
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                $errorMsg = $e->getMessage();
                
                if (strpos($errorMsg, 'raw card data') !== false || strpos($errorMsg, 'unsafe') !== false) {
                    $notify[] = ['error', 'Stripe Configuration Required: To use real cards, enable "Raw card data" API in Stripe Settings. For testing, use card 4242 4242 4242 4242 which works without configuration.'];
                } else {
                    $notify[] = ['error', 'Token Error: ' . $errorMsg];
                }
                return back()->withNotify($notify);
            } catch (\Exception $e) {
                $notify[] = ['error', 'Card Validation Error: ' . $e->getMessage()];
                return back()->withNotify($notify);
            }
        }

        // Process charge with token
        $cents = round($deposit->final_amount, 2) * 100;
        
        // Get Stripe account info
        $stripeAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        $isTestMode = strpos($stripeAcc->secret_key, 'sk_test_') === 0;
        
        // Check if this is a package payment with referral commission
        $user = auth()->user();
        $isPackagePayment = session('package_deposit.lock') === true;
        $referrerStripeAccount = null;
        $applicationFee = 0;
        $useStripeConnect = false;
        $referrer = null;
        
        if ($isPackagePayment && $user && $user->referred_by) {
            $referrer = \App\Models\User::find($user->referred_by);
            
            // Use Stripe Connect if referrer has connected account
            if ($referrer && $referrer->role === 'agent' && $referrer->stripe_connected && $referrer->stripe_account_id) {
                $referrerStripeAccount = $referrer->stripe_account_id;
                $useStripeConnect = true;
                
                // Calculate split: 10% goes to referrer, 90% stays with platform
                // NOTE: With Stripe destination charges:
                // - application_fee_amount = what the PLATFORM keeps (90%)
                // - destination account receives: charge amount - application fee (10%)
                $commissionRate = (gs('referral_commission_rate') ?? 10) / 100;
                $fullPackagePrice = session('package_deposit.full_package_price', $deposit->final_amount);
                
                // Platform keeps 90%, so application_fee = 90% of full price
                $platformPortion = $fullPackagePrice * (1 - $commissionRate);
                $applicationFee = round($platformPortion * 100); // In cents
                
                // Agent receives: full charge - application fee = 10%
                // Example: $99.00 charge
                // - application_fee = $89.10 (platform keeps)
                // - destination receives = $99.00 - $89.10 = $9.90 (agent gets)
                
                // Validate connected account before attempting charge (for better error messages)
                if ($useStripeConnect && $referrerStripeAccount) {
                    try {
                        Stripe::setApiKey($stripeAcc->secret_key);
                        Stripe::setApiVersion("2023-10-16");
                        
                        // Try to retrieve the account to validate it exists
                        $account = \Stripe\Account::retrieve($referrerStripeAccount);
                        
                        // Check if account is in correct mode
                        if ($isTestMode && $account->livemode) {
                            $notify[] = ['error', 'Stripe Connect Error: The agent account ID is a LIVE mode account, but you are using TEST mode API keys. Please use a test mode connected account ID or switch to live mode API keys.'];
                            return back()->withNotify($notify);
                        }
                        
                        if (!$isTestMode && !$account->livemode) {
                            $notify[] = ['error', 'Stripe Connect Error: The agent account ID is a TEST mode account, but you are using LIVE mode API keys. Please use a live mode connected account ID or switch to test mode API keys.'];
                            return back()->withNotify($notify);
                        }
                        
                        // Check account status - restricted accounts work fine in test mode
                        if (isset($account->charges_enabled) && !$account->charges_enabled) {
                            // Account might be restricted, but this is OK for test mode
                            if ($isTestMode) {
                                // In test mode, restricted accounts can still receive payments
                                // Just log it but don't block the payment
                            } else {
                                // In live mode, restricted accounts need verification
                                $notify[] = ['warning', 'Stripe Connect Warning: The connected account is restricted. Payments may be delayed until the account is verified. For test mode, this is fine.'];
                            }
                        }
                    } catch (\Stripe\Exception\InvalidRequestException $e) {
                        $errorMsg = $e->getMessage();
                        if (strpos($errorMsg, 'No such account') !== false) {
                            $notify[] = ['error', 'Stripe Connect Error (AGENT ACCOUNT): The connected account ID "' . $referrerStripeAccount . '" does not exist or is invalid. Please verify the Stripe Account ID in agent settings. For test mode, you need a test connected account ID (starts with acct_).'];
                            return back()->withNotify($notify);
                        }
                        // If validation fails but account might still work, continue
                    } catch (\Exception $e) {
                        // If validation fails, continue with charge attempt
                    }
                }
            }
        }

        try {
            // Charge full amount to platform account
            $chargeData = array(
                'source' => $stripeToken,
                'currency' => strtolower($deposit->method_currency),
                'amount' => $cents,
                'description' => 'Package purchase - ' . ($deposit->package_id ?? 'Deposit'),
            );
            
            // Create charge (full amount goes to platform)
            $charge = Charge::create($chargeData);

            if ($charge['status'] == 'succeeded') {
                // After successful charge, transfer commission to referrer if applicable
                $transferCreated = false;
                $commissionAmount = 0;
                
                // Check if we should transfer commission (recalculate here)
                if ($isPackagePayment && $user && $user->referred_by) {
                    $referrer = \App\Models\User::find($user->referred_by);
                    
                    if ($referrer && $referrer->role === 'agent' && $referrer->stripe_connected && $referrer->stripe_account_id) {
                        try {
                            // Calculate commission (10% of full package price)
                            $commissionRate = (gs('referral_commission_rate') ?? 10) / 100;
                            $fullPackagePrice = session('package_deposit.full_package_price', $deposit->final_amount);
                            $commissionAmount = round($fullPackagePrice * $commissionRate * 100); // In cents
                            
                            // Transfer commission to referrer's Stripe account
                            $transfer = \Stripe\Transfer::create([
                                'amount' => $commissionAmount,
                                'currency' => strtolower($deposit->method_currency),
                                'destination' => $referrer->stripe_account_id,
                                'description' => 'Referral commission (' . round($commissionRate * 100) . '%) from ' . $user->username . ' package purchase',
                            ]);
                            
                            $transferCreated = true;
                        } catch (\Stripe\Exception\InvalidRequestException $e) {
                            // Transfer failed, but payment succeeded - log it
                            \Log::error('Stripe Transfer Failed: ' . $e->getMessage(), [
                                'referrer_account' => $referrer->stripe_account_id ?? null,
                                'commission_amount' => $commissionAmount ?? 0,
                                'charge_id' => $charge->id,
                            ]);
                            // Continue - commission will be tracked internally
                        } catch (\Exception $e) {
                            \Log::error('Stripe Transfer Error: ' . $e->getMessage());
                            // Continue - commission will be tracked internally
                        }
                    }
                }
                
                // Update user data (this handles internal commission tracking)
                PaymentController::userDataUpdate($deposit);
                
                // Success message
                if ($transferCreated) {
                    $agentCommission = ($commissionAmount ?? 0) / 100;
                    $platformAmount = ($cents - ($commissionAmount ?? 0)) / 100;
                    $notify[] = ['success', 'Payment completed successfully! $' . number_format($platformAmount, 2) . ' to platform, $' . number_format($agentCommission, 2) . ' transferred to referrer via Stripe Connect.'];
                } else {
                    $notify[] = ['success', 'Payment completed successfully!'];
                }
                
                return redirect($deposit->success_url)->withNotify($notify);
            } else {
                $notify[] = ['error', 'Payment was not successful. Status: ' . $charge['status']];
            }
        } catch (\Stripe\Exception\CardException $e) {
            // Card was declined
            $notify[] = ['error', 'Card declined: ' . $e->getMessage()];
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters - check if it's a Stripe Connect error
            $errorMsg = $e->getMessage();
            $errorCode = $e->getStripeCode();
            
            // Check for specific Stripe Connect errors
            if (strpos($errorMsg, 'No such account') !== false || strpos($errorMsg, 'account') !== false || $errorCode === 'account_invalid') {
                if ($useStripeConnect && $referrerStripeAccount) {
                    // This is an AGENT account error
                    $notify[] = ['error', 'Stripe Connect Error (AGENT ACCOUNT): The connected account ID "' . $referrerStripeAccount . '" is invalid or not found. This is the agent\'s Stripe account. Please verify the Stripe Account ID in agent settings. For test mode, ensure you are using a test connected account ID.'];
                } else {
                    // This might be an ADMIN account error
                    $notify[] = ['error', 'Stripe Connect Error (ADMIN ACCOUNT): There may be an issue with the admin Stripe account configuration. Please verify that Stripe Connect is enabled on your admin Stripe account.'];
                }
            } elseif (strpos($errorMsg, 'application_fee') !== false || strpos($errorMsg, 'destination') !== false) {
                // Stripe Connect configuration error
                $notify[] = ['error', 'Stripe Connect Error: ' . $errorMsg . ' Please ensure Stripe Connect is enabled on your admin Stripe account and the connected account is properly set up.'];
            } else {
                $notify[] = ['error', 'Invalid payment request: ' . $errorMsg];
            }
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // This is an ADMIN account authentication error
            $notify[] = ['error', 'Stripe Authentication Error (ADMIN ACCOUNT): The admin Stripe API keys are invalid or expired. Please verify the Stripe API keys in gateway settings.'];
        } catch (\Exception $e) {
            $notify[] = ['error', 'Payment error: ' . $e->getMessage()];
        }

        return back()->withNotify($notify);
    }
}
