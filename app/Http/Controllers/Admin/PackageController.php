<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $pageTitle = 'Packages';
        $packages = Package::orderBy('id')->get();
        return view('admin.packages.index', compact('pageTitle','packages'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean'
        ]);
        $package = Package::findOrFail($id);
        $package->name = $request->name;
        $package->price = $request->price;
        $package->description = $request->description;
        $package->status = $request->boolean('status');
        $package->save();
        $notify[] = ['success','Package updated successfully'];
        return back()->withNotify($notify);
    }
    
    public function updateReferralBonus(Request $request)
    {
        $request->validate([
            'referral_bonus' => 'required|numeric|min:0',
        ]);
        
        $general = \App\Models\GeneralSetting::first();
        $general->referral_bonus = $request->referral_bonus;
        $general->save();
        
        $notify[] = ['success','Referral bonus updated successfully'];
        return back()->withNotify($notify);
    }
    
    public function toggleStatus($id)
    {
        $package = Package::findOrFail($id);
        $package->status = !$package->status;
        $package->save();
        $notify[] = ['success','Package status updated'];
        return back()->withNotify($notify);
    }
}


