<?php

namespace App\Http\Controllers;

use App\Models\EmployeeLoanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeLoanController extends Controller
{
    // Step 1: Personal Information
    public function wizard()
    {
        $pageTitle = 'Employee Loan - Personal Info';
        return view('Template::frontend.employee_loan.step1', compact('pageTitle'));
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

        $loanData = session('employee_loan', []);
        session(['employee_loan' => array_merge($loanData, $validated, ['current_step' => 1])]);

        $notify[] = ['success', 'Step 1 completed'];
        return redirect()->route('employee.loan.step2')->withNotify($notify);
    }

    // Step 2: Employment
    public function step2()
    {
        if (!session('employee_loan.first_name')) {
            $notify[] = ['error', 'Please complete Step 1 first'];
            return redirect()->route('employee.loan.wizard')->withNotify($notify);
        }

        $pageTitle = 'Employee Loan - Employment';
        return view('Template::frontend.employee_loan.step2', compact('pageTitle'));
    }

    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'employer_name' => 'required|string|max:150',
            'contract_type' => 'required|in:permanent,fixed_term,part_time',
            'monthly_net_income' => 'required|numeric|min:0',
            'employment_length_years' => 'required|integer|min:0',
            'employment_start_date' => 'nullable|date',
        ]);

        $loanData = session('employee_loan', []);
        session(['employee_loan' => array_merge($loanData, $validated, ['current_step' => 2])]);

        $notify[] = ['success', 'Step 2 completed'];
        return redirect()->route('employee.loan.step3')->withNotify($notify);
    }

    // Step 3: Loan Details
    public function step3()
    {
        if (!session('employee_loan.employer_name')) {
            $notify[] = ['error', 'Please complete Step 2 first'];
            return redirect()->route('employee.loan.step2')->withNotify($notify);
        }

        $pageTitle = 'Employee Loan - Loan Details';
        return view('Template::frontend.employee_loan.step3', compact('pageTitle'));
    }

    public function storeStep3(Request $request)
    {
        $validated = $request->validate([
            'loan_amount' => 'required|numeric|min:1000',
            'loan_duration_months' => 'required|integer|min:12|max:360',
            'loan_purpose' => 'required|in:home_purchase,renovation,debt_consolidation,personal_use,other',
            'loan_purpose_other' => 'nullable|string|max:200',
            'current_financing_details' => 'nullable|string',
            'current_financing_remaining' => 'nullable|numeric|min:0',
        ]);

        $loanData = session('employee_loan', []);
        session(['employee_loan' => array_merge($loanData, $validated, ['current_step' => 3])]);

        $notify[] = ['success', 'Step 3 completed'];
        return redirect()->route('employee.loan.step4')->withNotify($notify);
    }

    // Step 4: Documents
    public function step4()
    {
        if (!session('employee_loan.loan_amount')) {
            $notify[] = ['error', 'Please complete Step 3 first'];
            return redirect()->route('employee.loan.step3')->withNotify($notify);
        }

        $pageTitle = 'Employee Loan - Documents';
        return view('Template::frontend.employee_loan.step4', compact('pageTitle'));
    }

    public function storeStep4(Request $request)
    {
        $request->validate([
            'privacy_authorization' => 'required|accepted',
            'doc_valid_id' => 'required|file|mimes:pdf|max:102400',
            'doc_payslips' => 'required|file|mimes:pdf|max:102400',
            'doc_employment_contract' => 'required|file|mimes:pdf|max:102400',
            // Other documents optional
            'doc_certificate_residency' => 'nullable|file|mimes:pdf|max:102400',
            'doc_family_status' => 'nullable|file|mimes:pdf|max:102400',
            'doc_marital_status' => 'nullable|file|mimes:pdf|max:102400',
            'doc_health_card' => 'nullable|file|mimes:pdf|max:102400',
            'doc_residence_permit' => 'nullable|file|mimes:pdf|max:102400',
            'doc_passport' => 'nullable|file|mimes:pdf|max:102400',
            'doc_cu_2025' => 'nullable|file|mimes:pdf|max:102400',
            'doc_bank_statement' => 'nullable|file|mimes:pdf|max:102400',
            'doc_transactions_30days' => 'nullable|file|mimes:pdf|max:102400',
            'doc_inps_statement' => 'nullable|file|mimes:pdf|max:102400',
            'doc_loan_agreement' => 'nullable|file|mimes:pdf|max:102400',
            'doc_isee' => 'nullable|file|mimes:pdf|max:102400',
        ]);

        $loanData = session('employee_loan', []);
        $loanData['user_id'] = auth()->id();
        $loanData['status'] = 'pending';
        $loanData['current_step'] = 4;
        $loanData['privacy_authorization'] = true;

        // Upload documents
        $documentFields = [
            'doc_certificate_residency', 'doc_family_status', 'doc_marital_status', 'doc_valid_id',
            'doc_health_card', 'doc_residence_permit', 'doc_passport', 'doc_cu_2025',
            'doc_payslips', 'doc_bank_statement', 'doc_transactions_30days', 'doc_employment_contract',
            'doc_inps_statement', 'doc_loan_agreement', 'doc_isee'
        ];

        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('employee_loan_documents', 'public');
                $loanData[$field] = $path;
            }
        }

        EmployeeLoanApplication::create($loanData);

        session()->forget('employee_loan');

        $notify[] = ['success', 'Employee loan application submitted successfully! We will review your documents and contact you soon.'];
        return redirect()->route('home')->withNotify($notify);
    }
}
