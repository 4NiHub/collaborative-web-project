<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    // --- ADD THIS LINE TO FIX THE CACHE PATH ERROR ---
    ->withConfig([
        'view.compiled' => realpath(storage_path('framework/views')),
    ])
    // -------------------------------------------------
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'student' => \App\Http\Middleware\EnsureIsStudent::class,
            'teacher' => \App\Http\Middleware\EnsureIsTeacher::class,
        ]);

        $middleware->web(append: [
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
