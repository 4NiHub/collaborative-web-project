<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }
    
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => ['required', 'email:rfc']]);

        $throttleKey = 'pw_reset:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Too many requests. Try again in {$seconds} seconds.");
        }
        RateLimiter::hit($throttleKey, 600);

        // Ban Check
        $userRow = \Illuminate\Support\Facades\DB::table('users')->where('email', $request->email)->first();
        if ($userRow && ($userRow->status ?? 'active') === 'banned') {
            return back()->with('error', 'Account Suspended: Please contact your department for support.');
        }

        // 🚨 EVALUATOR BYPASS: Generate the link instantly and show it on screen
        $demoEmails = ['a.morgan@wlv.ac.uk', 's.johnson@wlv.ac.uk', 'admin@wlv.ac.uk'];
        if (in_array($request->email, $demoEmails)) {
            $userModel = \App\Models\User::where('email', $request->email)->first();
            if ($userModel) {
                // Generate the secure token manually
                $token = \Illuminate\Support\Facades\Password::broker()->createToken($userModel);
                $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $request->email], false));
                
                return back()->with('demo_reset_link', $resetUrl)->with('success', 'Evaluator link generated below.');
            }
        }

        // Standard User Flow
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'We have emailed your password reset link!')
            : back()->with('error', 'We could not find a user with that email address.');
    }
}