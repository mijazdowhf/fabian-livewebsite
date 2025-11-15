<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAgentSubscribed
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'agent') {
            if (!auth()->user()->profile_complete) {
                return to_route('agent.data');
            }
            if (!auth()->user()->paid_package_agent) {
                $notify[] = ['error', 'Please select a package and complete payment to unlock your agent dashboard.'];
                return to_route('agent.packages.choose')->withNotify($notify);
            }
        }
        return $next($request);
    }
}


