<?php

namespace App\Http\Controllers\Gateway\StripeJs;

use App\Constants\Status;
use App\Models\Deposit;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
// COMMENTED OUT - OLD STRIPE CODE - NOW USING STRIPE CONNECT
// use Stripe\Charge;
// use Stripe\Customer;
// use Stripe\Stripe;


class ProcessController extends Controller
{

    /*
     * OLD STRIPE JS CODE - COMMENTED OUT - NOW USING STRIPE CONNECT
     * See StripeConnect\ProcessController for new implementation
     */
    public static function process($deposit)
    {
        // OLD CODE COMMENTED OUT - Using Stripe Connect instead
        $notify[] = ['error', 'This Stripe gateway method is deprecated. Please use Stripe Connect.'];
        return json_encode(['error' => true, 'message' => 'This gateway method is deprecated.']);
        
        /* OLD CODE - COMMENTED OUT
        $StripeJSAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        $val['key'] = $StripeJSAcc->publishable_key;
        $val['name'] = auth()->user()->username;
        $val['description'] = "Payment with Stripe";
        $val['amount'] = round($deposit->final_amount,2) * 100;
        $val['currency'] = $deposit->method_currency;
        $send['val'] = $val;


        $alias = $deposit->gateway->alias;

        $send['src'] = "https://checkout.stripe.com/checkout.js";
        $send['view'] = 'user.payment.' . $alias;
        $send['method'] = 'post';
        $send['url'] = route('ipn.'.$deposit->gateway->alias);
        return json_encode($send);
        */
    }

    public function ipn(Request $request)
    {
        // OLD CODE COMMENTED OUT - Using Stripe Connect instead
        $notify[] = ['error', 'This Stripe gateway method is deprecated. Please use Stripe Connect.'];
        return back()->withNotify($notify);
        
        /* OLD CODE - COMMENTED OUT
        $track = Session::get('Track');
        $deposit = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();
        if ($deposit->status == Status::PAYMENT_SUCCESS) {
            $notify[] = ['error', 'Invalid request.'];
            return redirect($deposit->failed_url)->withNotify($notify);
        }
        $StripeJSAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);


        Stripe::setApiKey($StripeJSAcc->secret_key);

        Stripe::setApiVersion("2020-03-02");

        try {
            $customer =  Customer::create([
                'email' => $request->stripeEmail,
                'source' => $request->stripeToken,
            ]);
        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }

        try {
            $charge = Charge::create([
                'customer' => $customer->id,
                'description' => 'Payment with Stripe',
                'amount' => round($deposit->final_amount,2) * 100,
                'currency' => $deposit->method_currency,
            ]);
        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }


        if ($charge['status'] == 'succeeded') {
            PaymentController::userDataUpdate($deposit);
            $notify[] = ['success', 'Payment captured successfully'];
            return redirect($deposit->success_url)->withNotify($notify);
        }else{
            $notify[] = ['error', 'Failed to process'];
            return back()->withNotify($notify);
        }
        */
    }
}
