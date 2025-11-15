<?php

namespace App\Http\Controllers\Gateway\StripeConnect;

use App\Constants\Status;
use App\Models\Deposit;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Transfer;
use Stripe\Exception\ApiErrorException;

class ProcessController extends Controller
{
    /**
     * Stripe Connect Gateway - Using PaymentIntents and Transfers
     * 
     * Flow:
     * 1. Customer pays $100 to Admin (platform) account via PaymentIntent
     * 2. After successful payment, transfer 10% ($10) to referrer's Stripe account
     * 3. Remaining 90% ($90) stays in admin Stripe account automatically
     */
    public static function process($deposit)
    {
        $alias = $deposit->gateway->alias;
        $stripeAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        
        // Set Stripe API key
        Stripe::setApiKey($stripeAcc->secret_key);
        Stripe::setApiVersion("2023-10-16");
        
        // Get admin Stripe account ID from general settings
        $adminStripeAccountId = gs('admin_stripe_account_id');
        
        if (!$adminStripeAccountId) {
            $send['error'] = true;
            $send['message'] = 'Admin Stripe account is not connected. Please contact administrator.';
            return json_encode($send);
        }
        
        // Create PaymentIntent on platform account (admin account)
        // Note: We create the PaymentIntent on the platform account, not with stripe_account parameter
        // The platform account is the default account when using the secret key
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => round($deposit->final_amount, 2) * 100, // Convert to cents
                'currency' => strtolower($deposit->method_currency),
                'payment_method_types' => ['card'],
                'description' => 'Package purchase - ' . ($deposit->package_id ?? 'Deposit'),
                'metadata' => [
                    'deposit_trx' => $deposit->trx,
                    'user_id' => $deposit->user_id,
                    'admin_account_id' => $adminStripeAccountId,
                ],
            ]);
            
            $send['track'] = $deposit->trx;
            $send['view'] = 'user.payment.StripeConnect'; // Use StripeConnect view
            $send['method'] = 'post';
            // Use gateway alias to build route name (matches pattern used in other gateways)
            $send['url'] = route('ipn.' . ucfirst($alias)); // ucfirst converts 'stripe' to 'Stripe'
            $send['payment_intent_client_secret'] = $paymentIntent->client_secret;
            $send['publishable_key'] = $stripeAcc->publishable_key;
            
            // Store payment intent ID for later use
            $deposit->btc_wallet = $paymentIntent->id;
            $deposit->save();
            
            return json_encode($send);
            
        } catch (\Exception $e) {
            $send['error'] = true;
            $send['message'] = 'Error creating payment: ' . $e->getMessage();
            return json_encode($send);
        }
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
        
        $stripeAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        $adminStripeAccountId = gs('admin_stripe_account_id');
        
        if (!$adminStripeAccountId) {
            $notify[] = ['error', 'Admin Stripe account is not connected. Please contact administrator.'];
            return back()->withNotify($notify);
        }
        
        // Set Stripe API key
        Stripe::setApiKey($stripeAcc->secret_key);
        Stripe::setApiVersion("2023-10-16");
        
        // Retrieve PaymentIntent to check status
        try {
            // Get payment intent ID from request or deposit
            $paymentIntentId = $request->input('payment_intent') ?? $deposit->btc_wallet;
            
            if (!$paymentIntentId) {
                $notify[] = ['error', 'Payment intent not found.'];
                return back()->withNotify($notify);
            }
            
            // Update deposit with payment intent ID if not already set
            if (!$deposit->btc_wallet && $paymentIntentId) {
                $deposit->btc_wallet = $paymentIntentId;
                $deposit->save();
            }
            
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            if ($paymentIntent->status === 'succeeded') {
                // Payment successful - now transfer commission to referrer if applicable
                $transferCreated = false;
                $commissionAmount = 0;
                
                // Get user from deposit (more reliable than auth()->user() in IPN handler)
                $user = \App\Models\User::find($deposit->user_id);
                $isPackagePayment = session('package_deposit.lock') === true;
                
                // Check if this is a package payment with referral commission
                if ($isPackagePayment && $user && $user->referred_by) {
                    $referrer = \App\Models\User::find($user->referred_by);
                    
                    // Only transfer if referrer is an agent with connected Stripe account
                    if ($referrer && $referrer->role === 'agent' && $referrer->stripe_connected && $referrer->stripe_account_id) {
                        try {
                            // Calculate commission (10% of full package price)
                            $commissionRate = (gs('referral_commission_rate') ?? 10) / 100;
                            $fullPackagePrice = session('package_deposit.full_package_price', $deposit->final_amount);
                            $commissionAmount = round($fullPackagePrice * $commissionRate * 100); // In cents
                            
                            // Create transfer to referrer's Stripe account
                            $transfer = Transfer::create([
                                'amount' => $commissionAmount,
                                'currency' => strtolower($deposit->method_currency),
                                'destination' => $referrer->stripe_account_id,
                                'transfer_group' => 'package_' . $deposit->trx,
                                'description' => 'Referral commission (' . round($commissionRate * 100) . '%) from ' . $user->username . ' package purchase',
                                'metadata' => [
                                    'deposit_trx' => $deposit->trx,
                                    'referrer_id' => $referrer->id,
                                    'user_id' => $user->id,
                                ],
                            ]);
                            
                            $transferCreated = true;
                            
                        } catch (ApiErrorException $e) {
                            // Transfer failed, but payment succeeded - log it
                            Log::error('Stripe Transfer Failed: ' . $e->getMessage(), [
                                'referrer_account' => $referrer->stripe_account_id ?? null,
                                'commission_amount' => $commissionAmount ?? 0,
                                'payment_intent_id' => $paymentIntentId,
                            ]);
                            // Continue - commission will be tracked internally
                        } catch (\Exception $e) {
                            Log::error('Stripe Transfer Error: ' . $e->getMessage());
                            // Continue - commission will be tracked internally
                        }
                    }
                }
                
                // Update user data (this handles internal commission tracking)
                PaymentController::userDataUpdate($deposit);
                
                // Success message
                if ($transferCreated) {
                    $agentCommission = ($commissionAmount ?? 0) / 100;
                    $platformAmount = ($deposit->final_amount - ($commissionAmount ?? 0) / 100);
                    $notify[] = ['success', 'Payment completed successfully! $' . number_format($platformAmount, 2) . ' to platform, $' . number_format($agentCommission, 2) . ' transferred to referrer via Stripe Connect.'];
                } else {
                    $notify[] = ['success', 'Payment completed successfully!'];
                }
                
                return redirect($deposit->success_url)->withNotify($notify);
                
            } elseif ($paymentIntent->status === 'requires_payment_method') {
                $notify[] = ['error', 'Payment failed. Please try again with a different payment method.'];
                return back()->withNotify($notify);
            } elseif ($paymentIntent->status === 'canceled') {
                $notify[] = ['error', 'Payment was canceled.'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'Payment status: ' . $paymentIntent->status];
                return back()->withNotify($notify);
            }
            
        } catch (ApiErrorException $e) {
            $notify[] = ['error', 'Stripe Error: ' . $e->getMessage()];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Payment error: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }
}

