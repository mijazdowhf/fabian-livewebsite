<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAgent
{
    public function handle(Request $request, Closure $next): Response
    {
        // Must be authenticated
        if (!auth()->check()) {
            return redirect()->route('user.login');
        }
        
        $user = auth()->user();
        
        // STRICT: Only allow users with 'agent' role
        if ($user->role !== 'agent') {
            $notify[] = ['error', 'Access denied to agent dashboard.'];
            
            // If it's a regular user, redirect to user dashboard
            if ($user->role === null || $user->role === 'user') {
                return redirect()->route('user.home')->withNotify($notify);
            }
            
            // Otherwise redirect to home
            return redirect()->route('home')->withNotify($notify);
        }
        
        return $next($request);
    }
}


