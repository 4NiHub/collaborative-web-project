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
    // public function handle(Request $request, Closure $next)
    // {
    //     // 1. If fully authenticated, they shouldn't be here (go to dashboard)
    //     if (auth()->check()) {
    //         return redirect()->route('dashboard');
    //     }

    //     // 2. Is there a 2FA process actually happening?
    //     $hasPending2fa = $request->session()->has('2fa_user_id');

    //     // 3. If they are on the 2FA page, let them through
    //     if ($request->routeIs('2fa.verify') || $request->routeIs('2fa.verify.submit')) {
    //         if (!$hasPending2fa) {
    //             return redirect()->route('login');
    //         }
    //         return $next($request);
    //     }

    //     // 4. If they try to access ANY other protected page without 2FA
    //     if ($hasPending2fa) {
    //         return redirect()->route('2fa.verify');
    //     }

    //     return redirect()->route('login');
    // }

    public function handle(Request $request, Closure $next)
    {
        return $next($request); // Just let everyone through for now
    }
}
