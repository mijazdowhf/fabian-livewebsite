<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\Intended;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    use RegistersUsers;

    public function __construct()
    {
        parent::__construct();
    }

    public function showRegistrationForm()
    {
        $pageTitle = "Register";
        // Capture referral from direct signup URL (supports 'ref', 'tuagente' and legacy 'reference')
        if (request()->has('ref') && request('ref')) {
            session()->put('reference', request('ref'));
        } elseif (request()->has('tuagente') && request('tuagente')) {
            session()->put('reference', request('tuagente'));
        } elseif (request()->has('reference') && request('reference')) {
            session()->put('reference', request('reference'));
        }
        if(gs('registration')){
            Intended::identifyRoute();
            return view('Template::user.auth.register', compact('pageTitle'));
        }else{
            return view('Template::user.auth.registration_disabled', compact('pageTitle'));
        }
    }


    protected function validator(array $data)
    {

        $passwordValidation = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $agree = 'nullable';
        if (gs('agree')) {
            $agree = 'required';
        }

        $validationRules = [
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required|string|email|unique:users',
            'password'  => ['required', 'confirmed', $passwordValidation],
            'role'      => 'required|in:user,agent',
            'captcha'   => 'sometimes|required',
            'agree'     => $agree
        ];

        // Add agent-specific validation
        if (isset($data['role']) && $data['role'] === 'agent') {
            $validationRules['has_vat'] = 'required|in:0,1';
            $validationRules['vat_number'] = 'required_if:has_vat,1|nullable|string|max:50';
            $validationRules['agent_address'] = 'required|string|max:255';
            $validationRules['agent_city'] = 'required|string|max:100';
            $validationRules['agent_country'] = 'required|string|max:100';
            $validationRules['agent_phone'] = 'required|string|max:50';
            
            // Optional referral code validation
            if (!empty($data['referral_code'])) {
                $validationRules['referral_code'] = [
                    'nullable',
                    'string',
                    'max:20',
                    function ($attribute, $value, $fail) {
                        if ($value) {
                            $exists = \App\Models\User::where('role', 'agent')
                                ->where(function($q) use ($value) {
                                    $q->where('referral_code', $value)
                                      ->orWhere('username', $value);
                                })
                                ->exists();
                            
                            if (!$exists) {
                                $fail(__('The referral code is invalid.'));
                            }
                        }
                    },
                ];
            }
        }

        $validate = Validator::make($data, $validationRules, [
            'firstname.required' => 'The first name field is required',
            'lastname.required' => 'The last name field is required',
            'vat_number.required_if' => 'VAT number is required when you select "Yes, with VAT"',
            'agent_address.required' => 'Address is required for agents',
            'agent_city.required' => 'City is required for agents',
            'agent_country.required' => 'Country is required for agents',
            'agent_phone.required' => 'Phone number is required for agents',
        ]);

        return $validate;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $request->session()->regenerateToken();

        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }



        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }



    protected function create(array $data)
    {
        // Check for referral code from form or session
        $referralCode = $data['referral_code'] ?? session()->get('reference');
        $referUser = null;
        
        if ($referralCode) {
            // Try to find agent by referral code first, then by username
            $referUser = User::where('role', 'agent')
                ->where(function($q) use ($referralCode) {
                    $q->where('referral_code', $referralCode)
                      ->orWhere('username', $referralCode);
                })
                ->first();
        }

        //User Create
        $user            = new User();
        $user->email     = strtolower($data['email']);
        $user->firstname = $data['firstname'];
        $user->lastname  = $data['lastname'];
        $user->password  = Hash::make($data['password']);
        $user->role      = ($data['role'] ?? 'user') === 'agent' ? 'agent' : 'user';
        $user->ref_by    = $referUser ? $referUser->id : 0;
        $user->referred_by = $referUser ? $referUser->id : null;
        
        // If agent, save VAT and address information
        if ($user->role === 'agent') {
            $user->has_vat = isset($data['has_vat']) ? (bool)$data['has_vat'] : false;
            $user->vat_number = $user->has_vat && isset($data['vat_number']) ? $data['vat_number'] : null;
            $user->address = $data['agent_address'] ?? null;
            $user->city = $data['agent_city'] ?? null;
            $user->country_name = $data['agent_country'] ?? null;
            $user->mobile = $data['agent_phone'] ?? null;
            
            // Generate unique referral code for agent
            $user->referral_code = User::generateReferralCode();
        }
        
        $user->kv = gs('kv') ? Status::NO : Status::YES;
        $user->ev = gs('ev') ? Status::NO : Status::YES;
        $user->sv = gs('sv') ? Status::NO : Status::YES;
        $user->ts = Status::DISABLE;
        $user->tv = Status::ENABLE;
        $user->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        $adminNotification->save();


        //Login Log Create
        $ip        = getRealIP();
        $exist     = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();

        if ($exist) {
            $userLogin->longitude    = $exist->longitude;
            $userLogin->latitude     = $exist->latitude;
            $userLogin->city         = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country      = $exist->country;
        } else {
            $info                    = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude    = @implode(',', $info['long']);
            $userLogin->latitude     = @implode(',', $info['lat']);
            $userLogin->city         = @implode(',', $info['city']);
            $userLogin->country_code = @implode(',', $info['code']);
            $userLogin->country      = @implode(',', $info['country']);
        }

        $userAgent          = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os      = @$userAgent['os_platform'];
        $userLogin->save();


        return $user;
    }

    public function checkUser(Request $request){
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = User::where('email',$request->email)->exists();
            $exist['type'] = 'email';
            $exist['field'] = 'Email';
        }
        if ($request->mobile) {
            $exist['data'] = User::where('mobile',$request->mobile)->where('dial_code',$request->mobile_code)->exists();
            $exist['type'] = 'mobile';
            $exist['field'] = 'Mobile';
        }
        if ($request->username) {
            $exist['data'] = User::where('username',$request->username)->exists();
            $exist['type'] = 'username';
            $exist['field'] = 'Username';
        }
        return response($exist);
    }

    public function registered()
    {
        if (auth()->check() && auth()->user()->role === 'agent') {
            if (!auth()->user()->profile_complete) {
                return to_route('agent.data');
            }
            if (!auth()->user()->paid_package_agent) {
                return to_route('agent.packages.choose');
            }
            return to_route('agent.dashboard');
        }
        return to_route('user.home');
    }

}
