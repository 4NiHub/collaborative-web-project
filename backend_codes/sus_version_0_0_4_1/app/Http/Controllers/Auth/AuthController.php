<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\TwoFactorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ─────────────────────────────────────────────
    //  SHOW LOGIN
    // ─────────────────────────────────────────────
    // AuthController.php

    // public function showLogin() {
    //     // If already logged in, go to dashboard
    //     if (auth()->check()) {
    //         return redirect()->route('dashboard');
    //     }
    //     // If 2FA is pending, go to 2FA page
    //     if (session()->has('2fa_user_id')) {
    //         return redirect()->route('2fa.verify');
    //     }
    //     return view('auth.login');
    // }

    public function showLogin(Request $request) {
        // If the user is authenticated but the JS sent them here with a 'reason',
        // log them out of the session too to break the loop.
        if ($request->has('reason')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Only redirect to dashboard if they are logged in AND haven't been kicked out
        if (Auth::check() && !$request->has('reason')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    // ─────────────────────────────────────────────
    //  PROCESS LOGIN
    // ─────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $email = strtolower(trim($request->email));
        $throttleKey = 'login:' . $request->ip() . '|' . $email;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($request->password, $user->password_hash)) {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        // --- 2FA LOGIC START ---
        $code = $this->generateSecureOTP();
        $expiresAt = now()->addMinutes(3)->timestamp;
        $cacheKey = '2fa:' . $user->user_id;

        Cache::put($cacheKey, [
            'code_hash'  => Hash::make($code),
            'expires_at' => $expiresAt,
            'attempts'   => 0,
        ], 180);

        try {
            $user->notify(new TwoFactorCode($code));
        } catch (\Exception $e) {
            // If email fails locally, print the code to the log file so you can still log in!
            Log::info("DEV MODE - 2FA code for {$email}: {$code}");
        }

        // Stage the session, but DO NOT log the user in yet
        $request->session()->regenerate();
        $request->session()->put([
            '2fa_user_id'    => $user->user_id,
            '2fa_email'      => $email,
            '2fa_expires_at' => $expiresAt,
        ]);
        $request->session()->save();

        // CRITICAL FIX: Return JSON so your `api.js` doesn't crash on a redirect
        return response()->json([
            'data' => [
                '2fa_required' => true,
                'redirect'     => route('2fa.verify')
            ]
        ]);
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email'    => ['required', 'email'],
    //         'password' => ['required', 'string', 'min:6'],
    //     ]);

    //     $email = strtolower(trim($request->email));
    //     $throttleKey = 'login:' . $request->ip() . '|' . $email;

    //     if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
    //         $seconds = RateLimiter::availableIn($throttleKey);
    //         throw ValidationException::withMessages([
    //             'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
    //         ]);
    //     }

    //     $user = User::where('email', $email)->first();

    //     if (! $user || ! Hash::check($request->password, $user->password_hash)) {
    //         RateLimiter::hit($throttleKey, 60);
    //         throw ValidationException::withMessages([
    //             'email' => 'These credentials do not match our records.',
    //         ]);
    //     }

    //     RateLimiter::clear($throttleKey);

    //     // --- DIRECT LOGIN LOGIC START ---
        
    //     // 1. Log the user in via Web Guard (for Blade/Session)
    //     Auth::login($user);
    //     $request->session()->regenerate();
    //     $request->session()->save();

    //     // 2. Generate API Token
    //     $token = $user->createToken('sus-portal-auth-token')->plainTextToken;

    //     // 3. Update last login
    //     $user->update([
    //         'last_login_at' => now(),
    //         'last_login_ip' => $request->ip(),
    //     ]);

    //     // 4. Determine role name
    //     $roleName = match ($user->role_id) {
    //         1 => 'student',
    //         2 => 'teacher',
    //         3 => 'staff',
    //         4 => 'admin',
    //         default => 'unknown',
    //     };

    //     // 5. Return JSON (since your login page JS expects a JSON response)
    //     return response()->json([
    //         'success'  => true,
    //         'token'    => $token,
    //         'role'     => $roleName,
    //         'redirect' => route('dashboard'),
    //         'user' => [
    //             'id'    => $user->user_id,
    //             'email' => $user->email,
    //             'role'  => $roleName,
    //         ]
    //     ]);
    //     // --- DIRECT LOGIN LOGIC END ---
    // }

    // ─────────────────────────────────────────────
    //  SHOW 2FA PAGE
    // ─────────────────────────────────────────────
    public function show2FA()
    {
        if (!session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }

        // CRITICAL: Force the CSRF token to refresh for this new session
        // before the 2FA page loads its meta tags.
        session()->regenerateToken();

        return view('auth.two-factor');
    }

    // ─────────────────────────────────────────────
    //  VERIFY 2FA
    // ─────────────────────────────────────────────
    public function verify2FA(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^\d{6}$/'],
        ]);

        $userId = $request->session()->get('2fa_user_id');

        if (! $userId) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please log in again.');
        }

        // Rate limit OTP attempts
        $otpThrottle = '2fa_attempt:' . $userId;
        if (RateLimiter::tooManyAttempts($otpThrottle, 3)) {
            $request->session()->flush();
            return redirect()->route('login')
                ->with('error', 'Too many verification attempts. Please sign in again.');
        }

        $cacheKey = '2fa:' . $userId;
        $stored   = Cache::get($cacheKey);

        if (! $stored || now()->timestamp > $stored['expires_at']) {
            Cache::forget($cacheKey);
            $request->session()->flush();
            return redirect()->route('login')
                ->with('error', 'Your verification code has expired. Please sign in again.');
        }

        if (! Hash::check($request->code, $stored['code_hash'])) {
            RateLimiter::hit($otpThrottle, 60);
            $remaining = 3 - RateLimiter::attempts($otpThrottle);
            
            // Return JSON instead of back() if the request wants JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => "Invalid code. {$remaining} attempt(s) remaining."
                ], 422);
            }
            
            return back()->with('error', "Invalid code. {$remaining} attempt(s) remaining.");
        }

        // ── Success ──
        Cache::forget($cacheKey);
        RateLimiter::clear($otpThrottle);

        $user = User::findOrFail($userId);

        // Generate API token
        $token = $user->createToken('sus-portal-auth-token')->plainTextToken;

        // Determine frontend-friendly role name
        $roleName = match ($user->role_id) {
            1 => 'student',
            2 => 'teacher',
            3 => 'staff',
            4 => 'admin',
            default => 'unknown',
        };

        // Update last login (good security practice)
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Regenerate session (important for security even in API+Blade hybrid apps)
        $request->session()->regenerate();

        // Optional: keep web session login if you still have some Blade-only flows
        // (most people keep it during transition from Blade → SPA)
        Auth::login($user, $remember = false);

        // Clear 2FA pending data
        $request->session()->forget(['2fa_user_id', '2fa_email', '2fa_expires_at']);

        return response()->json([
            'success'  => true,
            'token'    => $token,
            'role'     => $roleName,               // ← frontend needs this
            'message'  => 'Verification successful',
            'redirect' => route('dashboard'),      // or '/dashboard' — both fine
            // Optional but very useful for immediate UI update without extra request
            'user' => [
                'id'       => $user->user_id,
                'email'    => $user->email,
                'full_name'=> $user->full_name,    // if you have accessor
                'role'     => $roleName,
            ]
        ]);
    }

    // ─────────────────────────────────────────────
    //  RESEND 2FA
    // ─────────────────────────────────────────────
    public function resend2FA(Request $request)
    {
        $userId = $request->session()->get('2fa_user_id');

        if (! $userId) {
            return response()->json(['error' => 'Session expired'], 401);
        }

        $resendKey = '2fa_resend:' . $userId;
        if (RateLimiter::tooManyAttempts($resendKey, 1)) {
            return response()->json(['error' => 'Please wait before requesting another code.'], 429);
        }
        RateLimiter::hit($resendKey, 60);

        $user      = User::findOrFail($userId);
        $code      = $this->generateSecureOTP();
        $expiresAt = now()->addMinutes(3)->timestamp;

        Cache::put('2fa:' . $userId, [
            'code_hash'  => Hash::make($code),
            'expires_at' => $expiresAt,
            'attempts'   => 0,
        ], 180);

        try {
            $user->notify(new TwoFactorCode($code));
        } catch (\Exception $e) {
            Log::info("DEV MODE resend - 2FA code for {$user->email}: {$code}");
        }

        $request->session()->put('2fa_expires_at', $expiresAt);

        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────
    //  LOGOUT
    // ─────────────────────────────────────────────
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Logged out']);
        }

        return redirect('/login');
    }
    // ─────────────────────────────────────────────
    //  HELPER
    // ─────────────────────────────────────────────
    private function generateSecureOTP(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
