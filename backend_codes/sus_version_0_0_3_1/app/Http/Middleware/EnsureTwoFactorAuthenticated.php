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
        // 1. If already fully logged in (auth() is active), go to dashboard
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
    
        // 2. If no 2FA session exists, send to login
        if (!$request->session()->has('2fa_user_id')) {
            return redirect()->route('login')->with('error', 'Please sign in first.');
        }
    
        // 3. CRITICAL: If they are already on a 2FA route, LET THEM THROUGH.
        // This prevents the infinite loop.
        if ($request->routeIs('2fa.verify') || $request->routeIs('2fa.verify.submit') || $request->routeIs('2fa.resend')) {
            return $next($request);
        }
    
        // 4. Otherwise, redirect to the verify page
        return redirect()->route('2fa.verify');
    }
}
