<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 3 = Staff, 4 = Admin
        if (! $user || (int) $user->role_id < 3) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Admin only.');
        }

        return $next($request);
    }
}