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
        
        // 1. THIS IS THE MOST IMPORTANT PART FOR DIGITAL OCEAN
        // It tells Laravel to trust the proxy and recognize HTTPS
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'student' => \App\Http\Middleware\EnsureIsStudent::class,
            'teacher' => \App\Http\Middleware\EnsureIsTeacher::class,
        ]);

        // 2. Ensuring CSRF is active for web routes
        $middleware->web(append: [
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
