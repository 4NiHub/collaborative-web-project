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
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // ─────────────────────────────────────────────
    //  PROCESS LOGIN
    // ─────────────────────────────────────────────
    public function login(Request $request)
    {
        // ── Validate format only (no DNS check — avoids internal domains failing)
        $request->validate([
            'email'    => ['required', 'email'],   // simple format check only
            'password' => ['required', 'string', 'min:6'],
        ]);

        $email = strtolower(trim($request->email));

        // ── Rate limit: 5 attempts per 60s per IP + email ──
        $throttleKey = 'login:' . $request->ip() . '|' . $email;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        // ── Look up user in PostgreSQL ──
        $user = User::where('email', $email)->first();

        // ── Verify password (runs even if user not found — prevents timing attacks) ──
        $validCredentials = $user && Hash::check($request->password, $user->password);

        if (! $validCredentials) {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        // ── Check account is active ──
        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Your account has been deactivated. Please contact support.',
            ]);
        }

        // ── Clear rate limiter on success ──
        RateLimiter::clear($throttleKey);

        // ── Generate secure 6-digit OTP ──
        $code      = $this->generateSecureOTP();
        $expiresAt = now()->addMinutes(10)->timestamp;
        $cacheKey  = '2fa:' . $user->id;

        // Store hashed OTP in cache (not the plain code)
        Cache::put($cacheKey, [
            'code_hash'  => Hash::make($code),
            'expires_at' => $expiresAt,
            'attempts'   => 0,
        ], 600);

        // ── Send OTP email ──
        try {
            $user->notify(new TwoFactorCode($code));
        } catch (\Exception $e) {
            Log::error('2FA email send failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            // Don't block login if mail isn't configured yet (dev mode)
            // In production remove this and let it fail properly
            Log::info("DEV MODE - 2FA code for {$user->email}: {$code}");
        }

        // ── Store 2FA session ──
        $request->session()->regenerate();
        $request->session()->put([
            '2fa_user_id'    => $user->id,
            '2fa_email'      => $user->email,
            '2fa_expires_at' => $expiresAt,
        ]);

        return redirect()->route('2fa.verify');
    }

    // ─────────────────────────────────────────────
    //  SHOW 2FA PAGE
    // ─────────────────────────────────────────────
    public function show2FA(Request $request)
    {
        if (! $request->session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }
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
            return back()->with('error', "Invalid code. {$remaining} attempt(s) remaining.");
        }

        // // ── Success ──
        // Cache::forget($cacheKey);
        // RateLimiter::clear($otpThrottle);

        // $user = User::findOrFail($userId);

        // $request->session()->forget(['2fa_user_id', '2fa_email', '2fa_expires_at']);
        // $request->session()->regenerate();

        // Auth::login($user, false);

        // $user->update([
        //     'last_login_at' => now(),
        //     'last_login_ip' => $request->ip(),
        // ]);

        // return redirect()->intended(route('dashboard'));


        
        
        // ── Success ──
        Cache::forget($cacheKey);
        RateLimiter::clear($otpThrottle);

        $user = User::findOrFail($userId);

        $request->session()->forget(['2fa_user_id', '2fa_email', '2fa_expires_at']);
        $request->session()->regenerate();

        Auth::login($user, false);

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Generate Sanctum token (named for easy revocation later)
        $token = $user->createToken('sus-portal-auth-token')->plainTextToken;

        // Return JSON with token → JS will save it to localStorage
        return response()->json([
            'success' => true,
            'token'   => $token,
            'message' => '2FA verified, login successful',
            'redirect' => route('dashboard') // or '/dashboard'
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
        $expiresAt = now()->addMinutes(10)->timestamp;

        Cache::put('2fa:' . $userId, [
            'code_hash'  => Hash::make($code),
            'expires_at' => $expiresAt,
            'attempts'   => 0,
        ], 600);

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
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ─────────────────────────────────────────────
    //  HELPER
    // ─────────────────────────────────────────────
    private function generateSecureOTP(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
