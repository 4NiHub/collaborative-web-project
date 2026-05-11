<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsTeacher
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $request->expectsJson()
                ? response()->json(['error' => 'Please log in first.'], 401)
                : redirect()->route('login');
        }

        if ((int) $user->role_id !== 2) {
            return $request->expectsJson()
                ? response()->json(['error' => 'This area is for teachers only.'], 403)
                : redirect()->route('dashboard')
                    ->with('error', 'This page is only available for teachers.');
        }

        return $next($request);
    }
}