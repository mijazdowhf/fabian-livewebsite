<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellingMortgageApplication;
use App\Models\User;
use Illuminate\Http\Request;

class SellingMortgageController extends Controller
{
    public function index()
    {
        $pageTitle = 'Home Mortgage Applications';
        $applications = SellingMortgageApplication::with(['user', 'agent'])
            ->searchable(['first_name', 'last_name', 'email', 'mobile', 'business_name', 'vat_number', 'application_id'])
            ->filter(['status', 'business_type', 'property_type'])
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        return view('admin.selling_mortgages.index', compact('pageTitle', 'applications'));
    }

    public function details($id)
    {
        $pageTitle = 'Selling Mortgage Details';
        $application = SellingMortgageApplication::with(['user', 'agent'])->findOrFail($id);
        $agents = User::where('role', 'agent')->where('paid_package_agent', 1)->orderBy('username')->get();
        return view('admin.selling_mortgages.details', compact('pageTitle', 'application', 'agents'));
    }
    
    public function assignAgent(Request $request, $id)
    {
        $request->validate(['agent_id' => 'nullable|exists:users,id']);
        $application = SellingMortgageApplication::findOrFail($id);
        $application->agent_id = $request->agent_id;
        $application->save();
        $notify[] = ['success', 'Agent assigned successfully'];
        return back()->withNotify($notify);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,processing,approved,rejected']);
        $application = SellingMortgageApplication::findOrFail($id);
        $application->status = $request->status;
        $application->save();
        $notify[] = ['success', 'Status updated'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        SellingMortgageApplication::findOrFail($id)->delete();
        $notify[] = ['success', 'Application deleted'];
        return back()->withNotify($notify);
    }
}
