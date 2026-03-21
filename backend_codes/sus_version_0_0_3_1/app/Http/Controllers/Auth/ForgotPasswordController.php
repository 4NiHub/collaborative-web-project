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

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }
    
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email:rfc'],
        ]);

        $email = strtolower(trim($request->email));

        // ── Rate limit: still active ──
        $throttleKey = 'pw_reset:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Too many requests. Try again in {$seconds} seconds.");
        }
        RateLimiter::hit($throttleKey, 600);

        // ── TEMPORARY: No email is sent yet ──
        // We just return a friendly "under construction" message
        $constructionMessage = '🔧 Password reset is currently in the process of building. '
                            . 'We\'ll send you a secure link as soon as it\'s ready. '
                            . 'Thank you for your patience!';
        // $user = User::where('email', $email)->first();

        // Always return the same message (prevents enumeration)
        return back()->with('status', $constructionMessage);
    }
}
