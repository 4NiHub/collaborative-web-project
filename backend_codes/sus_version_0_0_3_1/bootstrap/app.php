<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            // ── Your custom role middleware (already added earlier)
            'student'     => \App\Http\Middleware\EnsureIsStudent::class,
            'teacher'     => \App\Http\Middleware\EnsureIsTeacher::class,

            // ── Add this line for 2FA pending check
            // '2fa.pending' => \App\Http\Middleware\EnsureTwoFactorAuthenticated::class,
            
        ]);

        // ── ADD THIS ───────────────────────────────────────────────
        $middleware->web(append: [
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        ]);
    
        // Exclude CSRF check only for register POST
        // $middleware->excludeFromCsrfVerification([
        //     'register',           // matches /register POST
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
