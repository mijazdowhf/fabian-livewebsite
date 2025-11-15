<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function profile()
    {
        $pageTitle = 'Profile Setting';
        $user = auth()->user();
        return view('agent.profile', compact('pageTitle','user'));
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'nullable|string',
            'zip' => 'nullable|string',
            'has_vat' => 'required|boolean',
            'vat_number' => 'required_if:has_vat,1|nullable|string|max:50',
        ],[
            'firstname.required' => 'The first name field is required',
            'lastname.required' => 'The last name field is required',
            'address.required' => 'The address field is required',
            'city.required' => 'The city field is required',
            'vat_number.required_if' => 'VAT number is required when you select "Yes, with VAT"',
        ]);

        $user = auth()->user();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->has_vat = (bool)$request->has_vat;
        $user->vat_number = $user->has_vat ? $request->vat_number : null;
        $user->save();

        $notify[] = ['success', 'Profile updated successfully'];
        return back()->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Change Password';
        return view('agent.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {
        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', $passwordValidation],
        ]);

        $user = auth()->user();
        if (!Hash::check($request->current_password, $user->password)) {
            $notify[] = ['error', "Current password doesn't match!"];
            return back()->withNotify($notify);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully'];
        return back()->withNotify($notify);
    }
}


