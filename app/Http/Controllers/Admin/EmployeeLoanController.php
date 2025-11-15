<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeLoanApplication;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeLoanController extends Controller
{
    public function index()
    {
        $pageTitle = 'Employee Loan Applications';
        $applications = EmployeeLoanApplication::with(['user', 'agent'])
            ->searchable(['first_name', 'last_name', 'email', 'mobile', 'tax_code', 'employer_name', 'application_id'])
            ->filter(['status', 'contract_type', 'loan_purpose'])
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        return view('admin.employee_loans.index', compact('pageTitle', 'applications'));
    }

    public function details($id)
    {
        $pageTitle = 'Employee Loan Details';
        $application = EmployeeLoanApplication::with(['user', 'agent'])->findOrFail($id);
        $agents = User::where('role', 'agent')->where('paid_package_agent', 1)->orderBy('username')->get();
        return view('admin.employee_loans.details', compact('pageTitle', 'application', 'agents'));
    }
    
    public function assignAgent(Request $request, $id)
    {
        $request->validate(['agent_id' => 'nullable|exists:users,id']);
        $application = EmployeeLoanApplication::findOrFail($id);
        $application->agent_id = $request->agent_id;
        $application->save();
        $notify[] = ['success', 'Agent assigned successfully'];
        return back()->withNotify($notify);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,processing,approved,rejected']);
        $application = EmployeeLoanApplication::findOrFail($id);
        $application->status = $request->status;
        $application->save();
        $notify[] = ['success', 'Status updated'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        EmployeeLoanApplication::findOrFail($id)->delete();
        $notify[] = ['success', 'Application deleted'];
        return back()->withNotify($notify);
    }
}
