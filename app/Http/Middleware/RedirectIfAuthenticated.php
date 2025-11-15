<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {

        if (Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user() ?: auth()->user();
            if ($user && $user->role === 'agent') {
                if (!$user->package_paid_at) {
                    return to_route('agent.packages.choose');
                }
                return to_route('agent.dashboard');
            }
            return to_route('user.home');
        }

        return $next($request);

    }
}
