<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('login'));

// ── Public Auth Routes ───────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// // ── Two-Factor Authentication Routes ─────────────────────────────────────────
// Route::middleware('2fa.pending')->group(function () {
//     Route::get('/two-factor', [AuthController::class, 'show2FA'])->name('2fa.verify');
//     Route::post('/two-factor', [AuthController::class, 'verify2FA'])->name('2fa.verify.submit');
//     Route::post('/two-factor/resend', [AuthController::class, 'resend2FA'])->name('2fa.resend');
// });

// ── Protected Routes ─────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard — role-aware view selection
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        return match((int)$user->role_id) {
            1 => view('dashboard'),
            2 => view('teacher.dashboard'),
            default => view('dashboard')->with('error', 'Unsupported role'),
        };
    })->name('dashboard');

    // ── Student-only ─────────────────────────────────────────────────────────
    Route::middleware('student')->group(function () {
        Route::get('/timetable', fn() => view('timetable'))->name('timetable');
        Route::get('/modules',   fn() => view('modules'))->name('modules');
        Route::get('/records',   fn() => view('records'))->name('records');
        Route::get('/teachers',      fn() => view('teachers'))->name('teachers');
        Route::get('/help',          fn() => view('help'))->name('help');
    });

    // ── Teacher-only ─────────────────────────────────────────────────────────
    Route::middleware('teacher')->group(function () {
        Route::get('/teacher/timetable', fn() => view('teacher.timetable'))->name('teacher.timetable');
        Route::get('/teacher/modules',   fn() => view('teacher.modules'))->name('teacher.modules');
        Route::get('/teacher/profile',   fn() => view('teacher.profile'))->name('teacher.profile');
        Route::get('/teacher/help',   fn() => view('teacher.help'))->name('teacher.help');
        // add more teacher routes here later
    });

    // ── Shared pages (both roles) ────────────────────────────────────────────
    Route::get('/news',          fn() => view('news'))->name('news');
    Route::get('/career-centre', fn() => view('career-centre'))->name('career-centre');
    Route::get('/contact',       fn() => view('contact'))->name('contact');
});
