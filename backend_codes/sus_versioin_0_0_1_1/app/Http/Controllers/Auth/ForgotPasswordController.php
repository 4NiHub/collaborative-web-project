<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot-password form.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a password reset link.
     * Security note: always return a generic success message to prevent
     * email enumeration attacks.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email:rfc'],
        ]);

        $email = strtolower(trim($request->email));

        // ── Rate limit: 3 attempts / 10 min per IP ──
        $throttleKey = 'pw_reset:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Too many requests. Try again in {$seconds} seconds.");
        }
        RateLimiter::hit($throttleKey, 600);

        // Always use the same generic response (no email enumeration)
        $genericMessage = 'If that email is registered, a reset link has been sent. Check your inbox.';

        // Only actually send if user exists — silent otherwise
        $user = User::where('email', $email)->first();
        if ($user) {
            $status = Password::sendResetLink(['email' => $email]);
            // $status is Password::RESET_LINK_SENT or Password::INVALID_USER etc.
        }

        return back()->with('status', $genericMessage);
    }
}
