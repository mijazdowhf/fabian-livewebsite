<?php

namespace App\Http\Controllers;

use App\Models\SellingMortgageApplication;
use App\Models\User;
use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\LoanAccountCreated;

class SellingMortgageController extends Controller
{
    public function wizard()
    {
        $pageTitle = 'Selling Mortgage - Personal Info';
        $user = auth()->check() ? auth()->user() : null;
        return view('Template::frontend.selling_mortgage.step1', compact('pageTitle', 'user'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'date_of_birth' => 'required|date',
            'age' => 'required|integer|min:18|max:100',
            'tax_code' => 'required|string|max:40',
            'email' => 'required|email|max:100',
            'mobile' => 'required|string|max:40',
            'city' => 'required|string|max:100',
            'province' => 'nullable|string|max:10',
            'marital_status' => 'required|in:married,single,cohabiting',
            'family_status' => 'nullable|string|max:100',
            'family_members' => 'required|integer|min:1',
        ]);

        $data = session('selling_mortgage', []);
        session(['selling_mortgage' => array_merge($data, $validated, ['current_step' => 1])]);

        $notify[] = ['success', 'Step 1 completed'];
        return redirect()->route('selling.mortgage.step2')->withNotify($notify);
    }

    public function step2()
    {
        if (!session('selling_mortgage.first_name')) {
            $notify[] = ['error', 'Please complete Step 1 first'];
            return redirect()->route('selling.mortgage.wizard')->withNotify($notify);
        }

        $pageTitle = 'Selling Mortgage - Business';
        return view('Template::frontend.selling_mortgage.step2', compact('pageTitle'));
    }

    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:150',
            'vat_number' => 'required|string|max:50',
            'business_type' => 'required|in:sole_proprietor,partnership,corporation,other',
            'business_years' => 'required|integer|min:0',
            'annual_revenue' => 'required|numeric|min:0',
            'monthly_net_income' => 'required|numeric|min:0',
        ]);

        $data = session('selling_mortgage', []);
        session(['selling_mortgage' => array_merge($data, $validated, ['current_step' => 2])]);

        $notify[] = ['success', 'Step 2 completed'];
        return redirect()->route('selling.mortgage.step3')->withNotify($notify);
    }

    public function step3()
    {
        if (!session('selling_mortgage.business_name')) {
            $notify[] = ['error', 'Please complete Step 2 first'];
            return redirect()->route('selling.mortgage.step2')->withNotify($notify);
        }

        $pageTitle = 'Selling Mortgage - Details';
        return view('Template::frontend.selling_mortgage.step3', compact('pageTitle'));
    }

    public function storeStep3(Request $request)
    {
        $validated = $request->validate([
            'mortgage_amount' => 'required|numeric|min:10000',
            'mortgage_duration_months' => 'required|integer|min:60|max:360',
            'property_type' => 'required|in:residential,commercial,mixed',
            'property_location' => 'nullable|string|max:200',
            'mortgage_purpose' => 'nullable|string',
            'current_financing_details' => 'nullable|string',
            'current_financing_remaining' => 'nullable|numeric|min:0',
        ]);

        $data = session('selling_mortgage', []);
        session(['selling_mortgage' => array_merge($data, $validated, ['current_step' => 3])]);

        $notify[] = ['success', 'Step 3 completed'];
        return redirect()->route('selling.mortgage.step4')->withNotify($notify);
    }

    public function step4()
    {
        if (!session('selling_mortgage.mortgage_amount')) {
            $notify[] = ['error', 'Please complete Step 3 first'];
            return redirect()->route('selling.mortgage.step3')->withNotify($notify);
        }

        $pageTitle = 'Selling Mortgage - Documents';
        return view('Template::frontend.selling_mortgage.step4', compact('pageTitle'));
    }

    public function storeStep4(Request $request)
    {
        $request->validate([
            'privacy_authorization' => 'required|accepted',
            'doc_valid_id' => 'required|file|mimes:pdf|max:102400',
            'doc_vat_assignment' => 'nullable|file|mimes:pdf|max:102400',
            'doc_tax_return_2025' => 'nullable|file|mimes:pdf|max:102400',
            'doc_certificate_residency' => 'nullable|file|mimes:pdf|max:102400',
            'doc_family_status' => 'nullable|file|mimes:pdf|max:102400',
            'doc_marital_status' => 'nullable|file|mimes:pdf|max:102400',
            'doc_health_card' => 'nullable|file|mimes:pdf|max:102400',
            'doc_residence_permit' => 'nullable|file|mimes:pdf|max:102400',
            'doc_tax_return_2024' => 'nullable|file|mimes:pdf|max:102400',
            'doc_electronic_receipt_2025' => 'nullable|file|mimes:pdf|max:102400',
            'doc_electronic_receipt_2024' => 'nullable|file|mimes:pdf|max:102400',
            'doc_bank_statement' => 'nullable|file|mimes:pdf|max:102400',
            'doc_transactions_30days' => 'nullable|file|mimes:pdf|max:102400',
            'doc_loan_agreement' => 'nullable|file|mimes:pdf|max:102400',
        ]);

        $data = session('selling_mortgage', []);
        
        // Get or create user account
        $user = null;
        $tempPassword = null;
        
        if (auth()->check()) {
            $user = auth()->user();
        } else {
            // Check if user exists with this email
            $user = User::where('email', $data['email'])->first();
            
            if (!$user) {
                // Create new user account
                $tempPassword = Str::random(8);
                $username = strtolower(explode('@', $data['email'])[0]) . rand(100, 999);
                
                // Ensure username is unique
                while (User::where('username', $username)->exists()) {
                    $username = strtolower(explode('@', $data['email'])[0]) . rand(100, 999);
                }
                
                $user = new User();
                $user->firstname = $data['first_name'];
                $user->lastname = $data['last_name'];
                $user->email = $data['email'];
                $user->username = $username;
                $user->password = Hash::make($tempPassword);
                $user->role = 'user';
                $user->mobile = $data['mobile'];
                
                // Auto-fill profile data from mortgage application
                $user->city = $data['property_city'] ?? null;
                $user->state = $data['property_province'] ?? null;
                $user->country_name = 'Italy'; // Default for broker con valor
                $user->address = ($data['property_address'] ?? '') . ', ' . ($data['property_city'] ?? '') . ', ' . ($data['property_province'] ?? '');
                $user->zip = $data['property_postal_code'] ?? null;
                $user->dial_code = '+39'; // Italy country code
                $user->country_code = 'IT';
                
                // Mark profile as complete since we have all data from mortgage form
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
                        'Home Mortgage Application'
                    ));
                } catch (\Exception $e) {
                    Log::error('Failed to send mortgage account creation email: ' . $e->getMessage());
                }
            }
        }
        
        $data['user_id'] = $user->id;
        $data['status'] = 'pending';
        $data['current_step'] = 4;
        $data['privacy_authorization'] = true;

        $docFields = ['doc_certificate_residency','doc_family_status','doc_marital_status','doc_valid_id','doc_health_card','doc_residence_permit','doc_tax_return_2025','doc_tax_return_2024','doc_electronic_receipt_2025','doc_electronic_receipt_2024','doc_vat_assignment','doc_bank_statement','doc_transactions_30days','doc_loan_agreement'];

        foreach ($docFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('selling_mortgage_documents', 'public');
            }
        }

        SellingMortgageApplication::create($data);
        session()->forget('selling_mortgage');

        if ($tempPassword) {
            // Store credentials in session to display on next page
            session()->flash('account_created', [
                'username' => $user->username,
                'password' => $tempPassword,
                'email' => $user->email,
                'name' => $user->firstname . ' ' . $user->lastname
            ]);
            $notify[] = ['success', 'Mortgage application submitted! Your account has been created.'];
        } else {
            $notify[] = ['success', 'Mortgage application submitted successfully!'];
        }
        
        return redirect()->route('loan.application.success')->withNotify($notify);
    }
}
