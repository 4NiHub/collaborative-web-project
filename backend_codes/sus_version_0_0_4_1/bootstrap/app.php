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
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Critical for Sanctum/SPA Auth on production
        $middleware->statefulApi();
        
        // 2. Critical for Digital Ocean Load Balancers
        $middleware->trustProxies(at: '*');

        // 3. Register your custom role middleware aliases (FIXES THE 500 ERROR)
        $middleware->alias([
            '2fa.pending' => \App\Http\Middleware\EnsureTwoFactorAuthenticated::class,
            'student' => \App\Http\Middleware\EnsureIsStudent::class,
            'teacher' => \App\Http\Middleware\EnsureIsTeacher::class,
        ]);

        // 4. Disable CSRF for API and Logout to stop 419 errors
        $middleware->validateCsrfTokens(except: [
            'api/*',
            'logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();