<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\EmployeeLoanApplication;
use Illuminate\Http\Request;

class EmployeeLoanController extends Controller
{
    // List all employee loans assigned to this agent
    public function index()
    {
        $pageTitle = 'My Assigned Employee Loans';
        $emptyMessage = 'No employee loans assigned yet';
        $loans = EmployeeLoanApplication::where('agent_id', auth()->id())
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(getPaginate());
        
        return view('agent.employee_loans.index', compact('pageTitle', 'loans', 'emptyMessage'));
    }
    
    // View employee loan details
    public function details($id)
    {
        $pageTitle = 'Employee Loan Application Details';
        $loan = EmployeeLoanApplication::where('agent_id', auth()->id())
            ->with('user')
            ->findOrFail($id);
        
        return view('agent.employee_loans.details', compact('pageTitle', 'loan'));
    }
}
