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
        // If fully authenticated, skip 2FA page
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        // Must have a pending 2FA session
        if (! $request->session()->has('2fa_user_id')) {
            return redirect()->route('login')
                ->with('error', 'Please sign in first.');
        }

        return $next($request);
    }
}
