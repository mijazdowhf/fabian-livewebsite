<?php

namespace App\Http\Controllers\Gateway;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function deposit()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('name')->get();
        $pageTitle = 'Deposit Methods';
        return view('Template::user.payment.deposit', compact('gatewayCurrency', 'pageTitle'));
    }

    public function depositInsert(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'gateway' => 'required',
            'currency' => 'required',
        ]);


        $user = auth()->user();
        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->where('method_code', $request->gateway)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = ['error', 'Please follow deposit limit'];
            return back()->withNotify($notify);
        }

        $charge = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable = $request->amount + $charge;
        $finalAmount = $payable * $gate->rate;

        $data = new Deposit();
        $data->user_id = $user->id;
        $data->method_code = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount = $request->amount;
        $data->charge = $charge;
        $data->rate = $gate->rate;
        $data->final_amount = $finalAmount;
        $data->btc_amount = 0;
        $data->btc_wallet = "";
        $data->trx = getTrx();
        if (session()->has('package_deposit')) {
            $data->success_url = urlPath('agent.dashboard');
            $data->failed_url = urlPath('agent.packages.pay');
        } else {
            $data->success_url = urlPath('user.deposit.history');
            $data->failed_url = urlPath('user.deposit.history');
        }
        $data->save();
        session()->put('Track', $data->trx);
        
        // Determine redirect based on user role
        if (auth()->user()->role === 'agent') {
            return to_route('agent.deposit.confirm');
        }
        return to_route('user.deposit.confirm');
    }


    public function appDepositConfirm($hash)
    {
        try {
            $id = decrypt($hash);
        } catch (\Exception $ex) {
            abort(404);
        }
        $data = Deposit::where('id', $id)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->firstOrFail();
        $user = User::findOrFail($data->user_id);
        auth()->login($user);
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }


    public function depositConfirm()
    {
        $track = session()->get('Track');
        
        // Check if track exists
        if (!$track) {
            $notify[] = ['error', 'Invalid payment session. Please try again.'];
            // Check if this is an agent trying to pay for package
            if (auth()->check() && auth()->user()->role === 'agent' && !auth()->user()->paid_package_agent) {
                return to_route('agent.packages.choose')->withNotify($notify);
            }
            return to_route('user.deposit.index')->withNotify($notify);
        }
        
        // Try to find the deposit
        $deposit = Deposit::where('trx', $track)->orderBy('id', 'DESC')->with('gateway')->first();
        
        // If deposit not found
        if (!$deposit) {
            $notify[] = ['error', 'Payment record not found. Please try again.'];
            if (auth()->check() && auth()->user()->role === 'agent') {
                return to_route('agent.packages.choose')->withNotify($notify);
            }
            return to_route('user.deposit.index')->withNotify($notify);
        }
        
        // Check if payment was already completed
        if ($deposit->status == Status::PAYMENT_SUCCESS) {
            $user = User::find($deposit->user_id);
            $isPackagePayment = ($user && $user->role === 'agent');
            
            if ($isPackagePayment && $user->paid_package_agent) {
                $notify[] = ['success', 'Payment completed successfully! Welcome to your dashboard.'];
                return to_route('agent.dashboard')->withNotify($notify);
            } else if ($isPackagePayment) {
                // Payment success but agent not marked as paid yet - process now
                $user->paid_package_agent = 1;
                $user->save();
                $notify[] = ['success', 'Package activated successfully!'];
                return to_route('agent.dashboard')->withNotify($notify);
            } else {
                $notify[] = ['success', 'Deposit completed successfully!'];
                return to_route('user.deposit.history')->withNotify($notify);
            }
        }
        
        // If payment is still pending, continue with processing
        if ($deposit->status != Status::PAYMENT_INITIATE) {
            $notify[] = ['info', 'Payment is being processed. Please wait.'];
            if (auth()->check() && auth()->user()->role === 'agent') {
                return to_route('agent.dashboard')->withNotify($notify);
            }
            return to_route('user.deposit.history')->withNotify($notify);
        }

        if ($deposit->method_code >= 1000) {
            return to_route('user.deposit.manual.confirm');
        }

        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);

        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return back()->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if(@$data->session){
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        // Check if this is an agent package payment
        $user = User::find($deposit->user_id);
        $isPackagePayment = ($user && $user->role === 'agent' && session()->has('package_deposit'));
        
        if ($isPackagePayment) {
            // For agent package payment, show Stripe view
            $pageTitle = 'Complete Payment';
            return view("Template::$data->view", compact('data', 'pageTitle', 'deposit'));
        }

        $pageTitle = 'Payment Confirm';
        return view("Template::$data->view", compact('data', 'pageTitle', 'deposit'));
    }


    public static function userDataUpdate($deposit,$isManual = null)
    {
        if ($deposit->status == Status::PAYMENT_INITIATE || $deposit->status == Status::PAYMENT_PENDING) {
            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->save();

            $user = User::find($deposit->user_id);
            $methodName = $deposit->methodName();

            $isPackagePayment = ($user && $user->role === 'agent' && session()->has('package_deposit'));

            if ($isPackagePayment) {
                // Mark agent as paid; do NOT credit balance
                $user->paid_package_agent = 1;
                $user->save();

                // Get full package price from session
                $packageData = session()->get('package_deposit', []);
                $fullPackagePrice = $packageData['full_package_price'] ?? $deposit->amount;
                
                $transaction = new Transaction();
                $transaction->user_id = $deposit->user_id;
                $transaction->amount = $fullPackagePrice; // Log full package price, not just Stripe amount
                $transaction->post_balance = $user->balance; // unchanged
                $transaction->charge = $deposit->charge;
                $transaction->trx_type = '-';
                $transaction->details = 'Package purchase via ' . $methodName . ($user->referred_by ? ' (Referral discount applied)' : '');
                $transaction->trx = $deposit->trx;
                $transaction->remark = 'package_purchase';
                $transaction->save();

                // Process referral commission
                if ($user->referred_by) {
                    $referrer = User::find($user->referred_by);
                    
                    // Only credit commission if referrer is an agent
                    if ($referrer && $referrer->role === 'agent') {
                        // Calculate commission based on FULL package price
                        $commissionRate = (gs('referral_commission_rate') ?? 10) / 100;
                        $commission = $fullPackagePrice * $commissionRate;
                        
                        // If referrer has Stripe Connect, money is already transferred via Stripe
                        // Don't add to internal balance - just track for reporting
                        $useStripeConnect = $referrer->stripe_connected && $referrer->stripe_account_id;
                        
                        if (!$useStripeConnect) {
                            // No Stripe Connect - add to internal balance for manual withdrawal
                            $referrer->balance += $commission;
                        }
                        
                        // Always track commission for reporting (even if via Stripe Connect)
                        $referrer->commission_balance = ($referrer->commission_balance ?? 0) + $commission;
                        $referrer->total_commission_earned = ($referrer->total_commission_earned ?? 0) + $commission;
                        $referrer->save();
                        
                        // Log commission transaction
                        $commissionTransaction = new Transaction();
                        $commissionTransaction->user_id = $referrer->id;
                        $commissionTransaction->amount = $commission;
                        $commissionTransaction->post_balance = $referrer->balance; // Use regular balance for transaction
                        $commissionTransaction->charge = 0;
                        $commissionTransaction->trx_type = '+';
                        $stripeInfo = $useStripeConnect ? ' [Transferred via Stripe Connect to: ' . $referrer->stripe_email . ']' : '';
                        $commissionTransaction->details = 'Referral commission (' . round($commissionRate * 100) . '%) from ' . $user->username . ' package purchase ($' . number_format($fullPackagePrice, 2) . ')' . $stripeInfo;
                        $commissionTransaction->trx = getTrx();
                        $commissionTransaction->remark = 'referral_commission';
                        $commissionTransaction->save();
                        
                        // Notify admin about commission
                        $adminNotification = new AdminNotification();
                        $adminNotification->user_id = $referrer->id;
                        $adminNotification->title = 'Referral commission earned by ' . $referrer->username;
                        $adminNotification->click_url = urlPath('admin.users.detail', $referrer->id);
                        $adminNotification->save();
                    }
                }

                // Clear package flow flag from session
                session()->forget('package_deposit');
            } else {
                // Regular deposit flow: credit to balance and log deposit transaction
                $user->balance += $deposit->amount;
                $user->save();

                $transaction = new Transaction();
                $transaction->user_id = $deposit->user_id;
                $transaction->amount = $deposit->amount;
                $transaction->post_balance = $user->balance;
                $transaction->charge = $deposit->charge;
                $transaction->trx_type = '+';
                $transaction->details = 'Deposit Via ' . $methodName;
                $transaction->trx = $deposit->trx;
                $transaction->remark = 'deposit';
                $transaction->save();
                
                // Check if this user was referred and credit referral bonus to agent
                if ($user->referred_by) {
                    $referrer = User::find($user->referred_by);
                    if ($referrer && $referrer->role === 'agent') {
                        // Check if this is the first successful deposit (referral bonus should be paid once)
                        $previousDeposits = Deposit::where('user_id', $user->id)
                            ->where('status', Status::PAYMENT_SUCCESS)
                            ->where('id', '<', $deposit->id)
                            ->count();
                        
                        if ($previousDeposits === 0) {
                            // First successful deposit - pay referral bonus
                            $referralBonus = gs('referral_bonus') ?? 10.00;
                            
                            $referrer->balance += $referralBonus;
                            $referrer->save();
                            
                            // Log referral bonus transaction
                            $bonusTransaction = new Transaction();
                            $bonusTransaction->user_id = $referrer->id;
                            $bonusTransaction->amount = $referralBonus;
                            $bonusTransaction->post_balance = $referrer->balance;
                            $bonusTransaction->charge = 0;
                            $bonusTransaction->trx_type = '+';
                            $bonusTransaction->details = 'Referral bonus from ' . $user->username;
                            $bonusTransaction->trx = getTrx();
                            $bonusTransaction->remark = 'referral_bonus';
                            $bonusTransaction->save();
                        }
                    }
                }
            }

            if (!$isManual) {
                $adminNotification = new AdminNotification();
                $adminNotification->user_id = $user->id;
                $adminNotification->title = 'Deposit successful via '.$methodName;
                $adminNotification->click_url = urlPath('admin.deposit.successful');
                $adminNotification->save();
            }

            notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
                'method_name' => $methodName,
                'method_currency' => $deposit->method_currency,
                'method_amount' => showAmount($deposit->final_amount,currencyFormat:false),
                'amount' => showAmount($deposit->amount,currencyFormat:false),
                'charge' => showAmount($deposit->charge,currencyFormat:false),
                'rate' => showAmount($deposit->rate,currencyFormat:false),
                'trx' => $deposit->trx,
                'post_balance' => showAmount($user->balance)
            ]);
        }
    }

    public function manualDepositConfirm()
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        abort_if(!$data, 404);
        if ($data->method_code > 999) {
            $pageTitle = 'Deposit Confirm';
            $method = $data->gatewayCurrency();
            $gateway = $method->method;
            return view('Template::user.payment.manual', compact('data', 'pageTitle', 'method','gateway'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        abort_if(!$data, 404);
        $gatewayCurrency = $data->gatewayCurrency();
        $gateway = $gatewayCurrency->method;
        $formData = $gateway->form->form_data;

        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);


        $data->detail = $userData;
        $data->status = Status::PAYMENT_PENDING;
        $data->save();


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $data->user->id;
        $adminNotification->title = 'Deposit request from '.$data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details',$data->id);
        $adminNotification->save();

        notify($data->user, 'DEPOSIT_REQUEST', [
            'method_name' => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount' => showAmount($data->final_amount,currencyFormat:false),
            'amount' => showAmount($data->amount,currencyFormat:false),
            'charge' => showAmount($data->charge,currencyFormat:false),
            'rate' => showAmount($data->rate,currencyFormat:false),
            'trx' => $data->trx
        ]);

        $notify[] = ['success', 'You have deposit request has been taken'];
        return to_route('user.deposit.history')->withNotify($notify);
    }


}
