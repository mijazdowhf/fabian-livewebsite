<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = auth()->user();
            // Allow agents to proceed with package payment flow even if some verifications are pending
            if ($user->role === 'agent' && session()->has('package_deposit')) {
                return $next($request);
            }
            if ($user->status  && $user->ev  && $user->sv  && $user->tv) {
                return $next($request);
            } else {
                if ($request->is('api/*')) {
                    $notify[] = 'You need to verify your account first.';
                    return response()->json([
                        'remark'=>'unverified',
                        'status'=>'error',
                        'message'=>['error'=>$notify],
                        'data'=>[
                            'user'=>$user
                        ],
                    ]);
                }else{
                    // Redirect agents to agent authorization, users to user authorization
                    if ($user->role === 'agent') {
                        return to_route('agent.authorization');
                    }
                    return to_route('user.authorization');
                }
            }
        }
        abort(403);
    }
}
