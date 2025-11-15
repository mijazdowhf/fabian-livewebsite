<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Exception\ApiErrorException;

class StripeConnectController extends Controller
{
    /**
     * Show Stripe Connect settings page
     */
    public function index()
    {
        $pageTitle = 'Stripe Connect Settings';
        $general = gs();
        $adminStripeAccountId = $general->admin_stripe_account_id;
        $adminStripeAccount = null;
        $isConnected = false;
        
        // Get Stripe API keys from gateway
        $stripeGateway = \App\Models\GatewayCurrency::whereHas('method', function($q) {
            $q->where('alias', 'stripe');
        })->first();
        
        $stripeSecret = null;
        $stripeClientId = env('STRIPE_CONNECT_CLIENT_ID');
        
        if ($stripeGateway && $stripeGateway->gateway_parameter) {
            $params = json_decode($stripeGateway->gateway_parameter);
            $stripeSecret = $params->secret_key ?? null;
        }
        
        // If account ID exists, try to retrieve account info
        if ($adminStripeAccountId && $stripeSecret) {
            try {
                Stripe::setApiKey($stripeSecret);
                Stripe::setApiVersion("2023-10-16");
                
                $adminStripeAccount = Account::retrieve($adminStripeAccountId);
                $isConnected = $adminStripeAccount->charges_enabled && $adminStripeAccount->details_submitted;
            } catch (\Exception $e) {
                // Account might not exist or be invalid
                $adminStripeAccount = null;
            }
        }
        
        return view('admin.stripe_connect.index', compact('pageTitle', 'adminStripeAccountId', 'adminStripeAccount', 'isConnected', 'stripeClientId'));
    }
    
    /**
     * Connect admin Stripe account via OAuth
     */
    public function connect(Request $request)
    {
        $request->validate([
            'stripe_account_id' => 'required|string',
        ]);
        
        $stripeAccountId = $request->stripe_account_id;
        
        // Get Stripe API keys
        $stripeGateway = \App\Models\GatewayCurrency::whereHas('method', function($q) {
            $q->where('alias', 'stripe');
        })->first();
        
        if (!$stripeGateway || !$stripeGateway->gateway_parameter) {
            $notify[] = ['error', 'Stripe gateway is not configured.'];
            return back()->withNotify($notify);
        }
        
        $params = json_decode($stripeGateway->gateway_parameter);
        $stripeSecret = $params->secret_key ?? null;
        
        if (!$stripeSecret) {
            $notify[] = ['error', 'Stripe API keys are not configured.'];
            return back()->withNotify($notify);
        }
        
        try {
            Stripe::setApiKey($stripeSecret);
            Stripe::setApiVersion("2023-10-16");
            
            // Verify the account exists and is valid
            $account = Account::retrieve($stripeAccountId);
            
            // Save to general settings
            $general = gs();
            $general->admin_stripe_account_id = $stripeAccountId;
            $general->save();
            
            $notify[] = ['success', 'Admin Stripe account connected successfully!'];
            return back()->withNotify($notify);
            
        } catch (ApiErrorException $e) {
            $notify[] = ['error', 'Stripe Error: ' . $e->getMessage()];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Error connecting Stripe account: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }
    
    /**
     * Disconnect admin Stripe account
     */
    public function disconnect()
    {
        $general = gs();
        $general->admin_stripe_account_id = null;
        $general->save();
        
        $notify[] = ['success', 'Admin Stripe account disconnected successfully.'];
        return back()->withNotify($notify);
    }
}

