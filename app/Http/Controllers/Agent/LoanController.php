<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\LoanInquiry;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    // List all personal loans assigned to this agent
    public function index()
    {
        $pageTitle = 'My Assigned Loans';
        $emptyMessage = 'No loans assigned yet';
        $loans = LoanInquiry::where('agent_id', auth()->id())
            ->with('user')
            ->when(request()->search, function($query) {
                $query->where('application_id', 'like', '%'.request()->search.'%')
                      ->orWhere(function($q) {
                          $q->whereHas('user', function($userQuery) {
                              $userQuery->where('username', 'like', '%'.request()->search.'%');
                          });
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(getPaginate());
        
        return view('agent.loans.index', compact('pageTitle', 'loans', 'emptyMessage'));
    }
    
    // View loan details
    public function details($id)
    {
        $pageTitle = 'Loan Application Details';
        $loan = LoanInquiry::where('agent_id', auth()->id())
            ->with('user')
            ->findOrFail($id);
        
        return view('agent.loans.details', compact('pageTitle', 'loan'));
    }
}
