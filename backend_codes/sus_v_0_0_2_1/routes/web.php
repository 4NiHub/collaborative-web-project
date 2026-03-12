<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::group(['middleware' => 'web'], function () {
//     Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// });

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// 2FA routes (if separate)
Route::middleware('2fa.pending')->group(function () {
    Route::get('/two-factor', function () {
        return view('auth.two-factor');
    })->name('2fa.verify');

    Route::post('/two-factor', [AuthController::class, 'verify2fa']);
    Route::post('/two-factor/resend', [AuthController::class, 'resend2fa'])->name('2fa.resend');
});

// Authenticated area
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/timetable', fn() => view('timetable'))->name('timetable');
    Route::get('/modules',   fn() => view('modules'))->name('modules');
    Route::get('/records',   fn() => view('records'))->name('records');
    Route::get('/news',      fn() => view('news'))->name('news');
    Route::get('/teachers',  fn() => view('teachers'))->name('teachers');
    Route::get('/career-centre', fn() => view('career-centre'))->name('career-centre');
    Route::get('/contact',   fn() => view('contact'))->name('contact');
    Route::get('/help',   fn() => view('help'))->name('help');
});