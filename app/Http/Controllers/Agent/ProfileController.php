<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Country\Country as Countries; // if exists; otherwise we'll fetch via helper
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function agentData()
    {
        $pageTitle = 'Complete Agent Profile';
        // Reuse same data as user profile completion page
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $mobileCode = session('mobile_code');
        return view('agent.data', compact('pageTitle','countries','mobileCode'));
    }

    public function agentDataSubmit(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'country' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'city' => 'required',
        ],[
            'address.required' => 'The address field is required',
            'city.required' => 'The city field is required',
        ]);

        $user = auth()->user();
        $user->username = $request->username;
        $user->country_name = $request->country;
        $user->mobile = $request->mobile;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->profile_complete = 1;
        $user->save();

        return to_route('agent.packages.choose');
    }
}


