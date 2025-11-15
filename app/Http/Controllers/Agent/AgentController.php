<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmployeeLoanApplication;
use App\Models\LoanInquiry;
use App\Models\SellingMortgageApplication;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function dashboard()
    {
        $pageTitle = 'Agent Dashboard';
        return view('agent.dashboard', compact('pageTitle'));
    }

    public function referrals()
    {
        $pageTitle = 'My Referrals';
        $agent = auth()->user();
        
        // Get referred agents (only agents, not regular users)
        $referrals = User::where('referred_by', $agent->id)
            ->where('role', 'agent')
            ->orderByDesc('id')
            ->paginate(15);
        
        // Generate referral link using username
        $referralLink = route('user.register') . '?ref=' . $agent->username;
        
        // Get commission settings from general settings
        $commissionRate = gs('referral_commission_rate') ?? 10; // Default 10%
        
        return view('agent.referrals', compact('pageTitle', 'referrals', 'agent', 'referralLink', 'commissionRate'));
    }

    public function stripeSettings()
    {
        $pageTitle = 'Stripe Account Settings';
        $agent = auth()->user();
        
        // Get Stripe API keys from gateway
        $stripeGateway = \App\Models\GatewayCurrency::whereHas('method', function($q) {
            $q->where('alias', 'stripe');
        })->first();
        
        $stripeSecret = null;
        if ($stripeGateway && $stripeGateway->gateway_parameter) {
            $params = json_decode($stripeGateway->gateway_parameter);
            $stripeSecret = $params->secret_key ?? null;
        }
        
        return view('agent.stripe_settings', compact('pageTitle', 'agent', 'stripeSecret'));
    }

    public function createStripeAccount(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:100',
        ]);

        $agent = auth()->user();
        
        // Get Stripe API keys
        $stripeGateway = \App\Models\GatewayCurrency::whereHas('method', function($q) {
            $q->where('alias', 'stripe');
        })->first();
        
        if (!$stripeGateway || !$stripeGateway->gateway_parameter) {
            $notify[] = ['error', 'Stripe gateway is not configured. Please contact administrator.'];
            return back()->withNotify($notify);
        }
        
        $params = json_decode($stripeGateway->gateway_parameter);
        $stripeSecret = $params->secret_key ?? null;
        
        if (!$stripeSecret) {
            $notify[] = ['error', 'Stripe API keys are not configured. Please contact administrator.'];
            return back()->withNotify($notify);
        }

        try {
            // Initialize Stripe
            \Stripe\Stripe::setApiKey($stripeSecret);
            \Stripe\Stripe::setApiVersion("2023-10-16");
            
            // Create Express Connected Account
            $account = \Stripe\Account::create([
                'type' => 'express',
                'country' => 'US', // Default, can be made configurable
                'email' => $request->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
            ]);
            
            // Save account ID to user
            $agent->stripe_email = $request->email;
            $agent->stripe_account_id = $account->id;
            $agent->stripe_connected = false; // Not connected until onboarding complete
            $agent->save();
            
            // Create onboarding link
            $accountLink = \Stripe\AccountLink::create([
                'account' => $account->id,
                'refresh_url' => route('agent.stripe.onboarding.refresh'),
                'return_url' => route('agent.stripe.onboarding.complete'),
                'type' => 'account_onboarding',
            ]);
            
            // Redirect to Stripe onboarding
            return redirect($accountLink->url);
            
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            $notify[] = ['error', 'Stripe Error: ' . $e->getMessage()];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Error creating Stripe account: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function stripeOnboardingRefresh()
    {
        $agent = auth()->user();
        
        if (!$agent->stripe_account_id) {
            $notify[] = ['error', 'No Stripe account found. Please create one first.'];
            return redirect()->route('agent.stripe.settings')->withNotify($notify);
        }
        
        // Get Stripe API keys
        $stripeGateway = \App\Models\GatewayCurrency::whereHas('method', function($q) {
            $q->where('alias', 'stripe');
        })->first();
        
        if (!$stripeGateway || !$stripeGateway->gateway_parameter) {
            $notify[] = ['error', 'Stripe gateway is not configured.'];
            return redirect()->route('agent.stripe.settings')->withNotify($notify);
        }
        
        $params = json_decode($stripeGateway->gateway_parameter);
        $stripeSecret = $params->secret_key ?? null;
        
        try {
            \Stripe\Stripe::setApiKey($stripeSecret);
            \Stripe\Stripe::setApiVersion("2023-10-16");
            
            // Create new onboarding link
            $accountLink = \Stripe\AccountLink::create([
                'account' => $agent->stripe_account_id,
                'refresh_url' => route('agent.stripe.onboarding.refresh'),
                'return_url' => route('agent.stripe.onboarding.complete'),
                'type' => 'account_onboarding',
            ]);
            
            return redirect($accountLink->url);
            
        } catch (\Exception $e) {
            $notify[] = ['error', 'Error: ' . $e->getMessage()];
            return redirect()->route('agent.stripe.settings')->withNotify($notify);
        }
    }

    public function stripeOnboardingComplete()
    {
        $agent = auth()->user();
        
        if (!$agent->stripe_account_id) {
            $notify[] = ['error', 'No Stripe account found.'];
            return redirect()->route('agent.stripe.settings')->withNotify($notify);
        }
        
        // Get Stripe API keys
        $stripeGateway = \App\Models\GatewayCurrency::whereHas('method', function($q) {
            $q->where('alias', 'stripe');
        })->first();
        
        if (!$stripeGateway || !$stripeGateway->gateway_parameter) {
            $notify[] = ['error', 'Stripe gateway is not configured.'];
            return redirect()->route('agent.stripe.settings')->withNotify($notify);
        }
        
        $params = json_decode($stripeGateway->gateway_parameter);
        $stripeSecret = $params->secret_key ?? null;
        
        try {
            \Stripe\Stripe::setApiKey($stripeSecret);
            \Stripe\Stripe::setApiVersion("2023-10-16");
            
            // Retrieve account to check status
            $account = \Stripe\Account::retrieve($agent->stripe_account_id);
            
            // Check if onboarding is complete
            if ($account->charges_enabled && $account->details_submitted) {
                $agent->stripe_connected = true;
                $agent->stripe_connected_at = now();
                $agent->save();
                
                $notify[] = ['success', 'Stripe account connected successfully! You can now receive referral commissions.'];
            } else {
                $notify[] = ['warning', 'Onboarding not yet complete. Please finish the setup process.'];
            }
            
            return redirect()->route('agent.stripe.settings')->withNotify($notify);
            
        } catch (\Exception $e) {
            $notify[] = ['error', 'Error checking account status: ' . $e->getMessage()];
            return redirect()->route('agent.stripe.settings')->withNotify($notify);
        }
    }

    public function commissionHistory()
    {
        $pageTitle = 'Commission Transaction History';
        $agent = auth()->user();
        
        // Get all referral commission transactions
        $transactions = \App\Models\Transaction::where('user_id', $agent->id)
            ->where('remark', 'referral_commission')
            ->orderBy('created_at', 'desc')
            ->paginate(getPaginate());
        
        return view('agent.commission_history', compact('pageTitle', 'agent', 'transactions'));
    }

    public function searchApplication(Request $request)
    {
        $pageTitle = 'Search Application';
        $searchResults = null;
        
        if ($request->has('application_id') && $request->application_id) {
            $searchResults = $this->searchApplications($request->application_id);
        }
        
        return view('agent.applications.search', compact('pageTitle', 'searchResults'));
    }

    private function searchApplications($searchTerm)
    {
        $agentId = auth()->id();
        $results = collect();

        // Search in loan inquiries (LoanInquiry - Personal Loans with LN prefix)
        $loanInquiries = LoanInquiry::where('agent_id', $agentId)
            ->where(function($query) use ($searchTerm) {
                $query->where('application_id', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhereHas('user', function($q) use ($searchTerm) {
                          $q->where('email', 'LIKE', '%' . $searchTerm . '%');
                      });
            })
            ->with('user')
            ->get()
            ->map(function($loan) {
                $loan->type = 'personal_loan';
                return $loan;
            });

        // Search in employee loans (EmployeeLoanApplication)
        $employeeLoans = EmployeeLoanApplication::where('agent_id', $agentId)
            ->where(function($query) use ($searchTerm) {
                $query->where('application_id', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhereHas('user', function($q) use ($searchTerm) {
                          $q->where('email', 'LIKE', '%' . $searchTerm . '%');
                      });
            })
            ->with('user')
            ->get()
            ->map(function($loan) {
                $loan->type = 'employee_loan';
                return $loan;
            });

        // Search in mortgages (SellingMortgageApplication)
        $mortgages = SellingMortgageApplication::where('agent_id', $agentId)
            ->where(function($query) use ($searchTerm) {
                $query->where('application_id', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhereHas('user', function($q) use ($searchTerm) {
                          $q->where('email', 'LIKE', '%' . $searchTerm . '%');
                      });
            })
            ->with('user')
            ->get()
            ->map(function($mortgage) {
                $mortgage->type = 'mortgage';
                return $mortgage;
            });

        // Merge all results
        $results = $loanInquiries->merge($employeeLoans)->merge($mortgages)->sortByDesc('created_at');

        return $results;
    }
}


