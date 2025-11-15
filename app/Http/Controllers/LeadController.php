<?php

namespace App\Http\Controllers;

use App\Models\LoanInquiry;
use App\Models\User;
use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\LoanAccountCreated;

class LeadController extends Controller
{
    // Loan Type Selector
    public function loanTypeSelector()
    {
        $pageTitle = 'Choose Loan Type';
        return view('Template::frontend.loan_type_selector', compact('pageTitle'));
    }
    
    // Success page showing login credentials
    public function applicationSuccess()
    {
        $pageTitle = 'Application Submitted Successfully';
        $accountData = session('account_created');
        return view('Template::frontend.application_success', compact('pageTitle', 'accountData'));
    }

    // Step 1: Personal Information
    public function wizard()
    {
        $pageTitle = 'Loan Application';
        $user = auth()->check() ? auth()->user() : null;
        return view('Template::frontend.personal_loan.step1', compact('pageTitle', 'user'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'loan_type' => 'required|in:personal_loan,microcredit,leasing,salary_secured',
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'date_of_birth' => 'required|date',
            'age' => 'required|integer|min:18|max:100',
            'tax_code' => 'required|string|max:40',
            'email' => 'required|email|max:100',
            'mobile' => 'required|string|max:40',
            'city' => 'required|string|max:100',
            'province' => 'nullable|string|max:10',
            'marital_status' => 'required|in:married,single,cohabiting,divorced',
            'applicant_type' => 'required|in:single,joint',
            'family_members' => 'required|integer|min:1',
        ]);

        // Store in session
        $loanData = session('loan_data', []);
        session(['loan_data' => array_merge($loanData, $validated, ['current_step' => 1])]);

        $notify[] = ['success', 'Step 1 completed successfully'];
        return redirect()->route('lead.step2')->withNotify($notify);
    }

    // Step 2: Employment Information
    public function step2()
    {
        if (!session('loan_data.first_name')) {
            $notify[] = ['error', 'Please complete Step 1 first'];
            return redirect()->route('lead.wizard')->withNotify($notify);
        }

        $pageTitle = 'Loan Application - Employment';
        return view('Template::frontend.personal_loan.step2', compact('pageTitle'));
    }

    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'occupation' => 'required|string|max:100',
            'employment_duration_type' => 'required|in:fixed_term,permanent,vat_number',
            'contract_type' => 'required|in:private,public,self_employed,retired',
            'monthly_net_income' => 'required|numeric|min:0',
            'employment_length_years' => 'required|integer|min:0',
        ]);

        // Store in session
        $loanData = session('loan_data', []);
        session(['loan_data' => array_merge($loanData, $validated, ['current_step' => 2])]);

        $notify[] = ['success', 'Step 2 completed successfully'];
        return redirect()->route('lead.step3')->withNotify($notify);
    }

    // Step 3: Loan Details
    public function step3()
    {
        if (!session('loan_data.occupation')) {
            $notify[] = ['error', 'Please complete Step 2 first'];
            return redirect()->route('lead.step2')->withNotify($notify);
        }

        $pageTitle = 'Loan Application - Details';
        return view('Template::frontend.personal_loan.step3', compact('pageTitle'));
    }

    public function storeStep3(Request $request)
    {
        $validated = $request->validate([
            'application_type' => 'nullable|in:personal_loan',
            'loan_purpose' => 'required|in:home_furnishings,debt_consolidation,liquidity,vacation,health,other',
            'loan_purpose_other' => 'nullable|string|max:200',
            'has_current_financing' => 'required|boolean',
            'current_financing_details' => 'nullable|string',
            'current_financing_remaining' => 'nullable|numeric|min:0',
            'privacy_authorization' => 'required|accepted',
        ]);

        // Merge with session data
        $loanData = session('loan_data', []);
        $allData = array_merge($loanData, $validated);
        
        // Get or create user account
        $user = null;
        $tempPassword = null;
        
        if (auth()->check()) {
            $user = auth()->user();
        } else {
            // Check if user exists with this email
            $user = User::where('email', $allData['email'])->first();
            
            if (!$user) {
                // Create new user account
                $tempPassword = Str::random(8);
                $username = strtolower(explode('@', $allData['email'])[0]) . rand(100, 999);
                
                // Ensure username is unique
                while (User::where('username', $username)->exists()) {
                    $username = strtolower(explode('@', $allData['email'])[0]) . rand(100, 999);
                }
                
                $user = new User();
                $user->firstname = $allData['first_name'];
                $user->lastname = $allData['last_name'];
                $user->email = $allData['email'];
                $user->username = $username;
                $user->password = Hash::make($tempPassword);
                $user->role = 'user';
                $user->mobile = $allData['mobile'];
                
                // Auto-fill profile data from loan application
                $user->city = $allData['municipality'] ?? null;
                $user->state = $allData['province'] ?? null;
                $user->country_name = 'Italy'; // Default for broker con valor
                $user->address = ($allData['municipality'] ?? '') . ', ' . ($allData['province'] ?? '');
                $user->dial_code = '+39'; // Italy country code
                $user->country_code = 'IT';
                
                // Mark profile as complete since we have all data from loan form
                $user->profile_complete = Status::YES;
                
                $user->kv = Status::YES;
                $user->ev = Status::YES;
                $user->sv = Status::YES;
                $user->ts = Status::DISABLE;
                $user->tv = Status::ENABLE;
                $user->save();
                
                // Send email with login credentials using Laravel Mailable
                try {
                    Mail::to($user->email)->send(new LoanAccountCreated(
                        $user->firstname . ' ' . $user->lastname,
                        $user->email,
                        $username,
                        $tempPassword,
                        route('user.login'),
                        'Loan Application'
                    ));
                } catch (\Exception $e) {
                    Log::error('Failed to send loan account creation email: ' . $e->getMessage());
                }
            }
        }
        
        $allData['application_type'] = 'personal_loan';
        $allData['user_id'] = $user->id;
        $allData['current_step'] = 3;
        $allData['status'] = 'pending';
        $allData['privacy_authorization'] = true;
        $allData['has_current_financing'] = (bool)$request->has_current_financing;

        // Save to database
        LoanInquiry::create($allData);

        // Clear session
        session()->forget('loan_data');

        if ($tempPassword) {
            // Store credentials in session to display on next page
            session()->flash('account_created', [
                'username' => $user->username,
                'password' => $tempPassword,
                'email' => $user->email,
                'name' => $user->firstname . ' ' . $user->lastname
            ]);
            $notify[] = ['success', 'Loan application submitted! Your account has been created.'];
        } else {
            $notify[] = ['success', 'Loan application submitted successfully! We will contact you soon.'];
        }
        
        return redirect()->route('loan.application.success')->withNotify($notify);
    }

    // Legacy single-page form (keeping for backwards compatibility)
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'date_of_birth' => 'required|date',
            'tax_code' => 'required|string|max:40',
            'email' => 'required|email|max:100',
            'mobile' => 'required|string|max:40',
            'city' => 'required|string|max:100',
            'employment_type' => 'required|in:employee,self_employed,retired,other',
            'net_income' => 'required|numeric|min:0',
            'loan_purpose' => 'required|string|max:120',
            'has_current_financing' => 'required|boolean',
            'current_financing_remaining' => 'nullable|numeric|min:0'
        ]);

        $data = $request->only([
            'first_name','last_name','date_of_birth','tax_code','email','mobile','city','employment_type','net_income','loan_purpose','loan_purpose_other','has_current_financing','current_financing_remaining'
        ]);
        $data['user_id'] = auth()->id();
        $data['has_current_financing'] = (bool)$request->has_current_financing;
        LoanInquiry::create($data);

        $notify[] = ['success','Richiesta inviata con successo'];
        return back()->withNotify($notify);
    }
}
