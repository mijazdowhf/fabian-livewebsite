<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanInquiry;
use App\Models\User;
use Illuminate\Http\Request;

class PersonalLoanController extends Controller
{
    public function index()
    {
        $pageTitle = 'Personal Loan Applications';
        $applications = LoanInquiry::with(['user', 'agent'])
            ->searchable(['first_name', 'last_name', 'email', 'mobile', 'tax_code', 'application_id'])
            ->filter(['status', 'occupation', 'loan_purpose'])
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        return view('admin.personal_loans.index', compact('pageTitle', 'applications'));
    }

    public function details($id)
    {
        $pageTitle = 'Personal Loan Application Details';
        $application = LoanInquiry::with(['user', 'agent'])->findOrFail($id);
        $agents = User::where('role', 'agent')->where('paid_package_agent', 1)->orderBy('username')->get();

        return view('admin.personal_loans.details', compact('pageTitle', 'application', 'agents'));
    }
    
    public function assignAgent(Request $request, $id)
    {
        $request->validate([
            'agent_id' => 'nullable|exists:users,id',
        ]);

        $application = LoanInquiry::findOrFail($id);
        $application->agent_id = $request->agent_id;
        $application->save();

        $notify[] = ['success', 'Agent assigned successfully'];
        return back()->withNotify($notify);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,approved,rejected',
        ]);

        $application = LoanInquiry::findOrFail($id);
        $application->status = $request->status;
        $application->save();

        $notify[] = ['success', 'Status updated successfully'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $application = LoanInquiry::findOrFail($id);
        $application->delete();

        $notify[] = ['success', 'Application deleted successfully'];
        return back()->withNotify($notify);
    }
}

