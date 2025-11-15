<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRegularUser
{
    public function handle(Request $request, Closure $next)
    {
        // Must be authenticated
        if (!auth()->check()) {
            return redirect()->route('user.login');
        }
        
        $user = auth()->user();
        
        // Block agents from accessing user dashboard
        if ($user->role === 'agent') {
            // ONLY allow agent to access deposit routes when paying for package
            // This is checked by the presence of 'package_deposit' session
            $routeName = optional($request->route())->getName();
            
            if ($routeName && str_starts_with($routeName, 'user.deposit.') && session()->has('package_deposit')) {
                return $next($request);
            }
            
            // Block all other agent access to user dashboard
            $notify[] = ['error', __('Access denied to user dashboard.')];
            return redirect()->route('agent.dashboard')->withNotify($notify);
        }
        
        // Ensure only regular users can access
        if ($user->role !== null && $user->role !== 'user') {
            $notify[] = ['error', __('You are logged in as an agent. Please use the agent dashboard.')];
            return redirect()->route('agent.dashboard')->withNotify($notify);
        }
        
        return $next($request);
    }
}


