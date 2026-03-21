<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protects the 2FA verification route:
 *  – Redirects to login if no 2FA session exists
 *  – Redirects to dashboard if user is already fully authenticated
 */
class EnsureTwoFactorAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. If the user is ALREADY fully logged in, they shouldn't be here.
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
    
        // 2. If there is NO 2FA session, they shouldn't be here.
        if (!$request->session()->has('2fa_user_id')) {
            return redirect()->route('login')->with('error', 'Please sign in first.');
        }
    
        // 3. IMPORTANT: Prevent session bloat. 
        // If the request is a standard GET request to the 2FA page, 
        // just let it through.
        return $next($request);
    }
}
