<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function choose()
    {
        $pageTitle = 'Choose Package';
        $packages = Package::where('status', true)->orderBy('price')->get();
        return view('agent.packages.choose', compact('pageTitle','packages'));
    }

    public function select(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id'
        ]);
        $package = Package::findOrFail($request->package_id);
        $user = auth()->user();
        $user->package_id = $package->id;
        $user->save();

        // For Stripe Connect split payments:
        // - Always charge FULL package price
        // - Stripe automatically splits: platform keeps 90%, agent gets 10%
        // - Customer pays full price, split happens at Stripe level
        $packagePrice = (float)$package->price;

        // Stash the package amount in session to prefill and lock deposit amount
        session()->put('package_deposit', [
            'amount' => $packagePrice,  // Always charge full price
            'full_package_price' => $packagePrice,
            'package_id' => $package->id,
            'lock' => true,
            'has_referrer' => (bool)$user->referred_by,
        ]);
        
        $notify[] = ['info', 'Proceed to payment to unlock your dashboard.'];
        return redirect()->route('agent.packages.pay')->withNotify($notify);
    }

    public function pay()
    {
        // Reuse deposit page logic but in a clean layout; simply render a dedicated view
        $pageTitle = 'Package Payment';
        // Keep gateway list same as deposit
        $gatewayCurrency = \App\Models\GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', \App\Constants\Status::ENABLE);
        })->with('method')->orderby('name')->get();
        return view('agent.packages.pay', compact('pageTitle','gatewayCurrency'));
    }
}


