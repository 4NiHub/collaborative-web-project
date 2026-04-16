<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $request->expectsJson()
                ? response()->json(['error' => 'Please log in first.'], 401)
                : redirect()->route('login');
        }

        if ((int) $user->role_id !== 1) {
            return $request->expectsJson()
                ? response()->json(['error' => 'This area is for students only.'], 403)
                : redirect()->route('dashboard')
                    ->with('error', 'This page is only available for students.');
        }

        return $next($request);
    }
}