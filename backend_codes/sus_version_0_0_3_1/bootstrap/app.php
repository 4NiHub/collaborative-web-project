<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// 1. Initialize the application builder
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'student' => \App\Http\Middleware\EnsureIsStudent::class,
            'teacher' => \App\Http\Middleware\EnsureIsTeacher::class,
        ]);

        $middleware->web(append: [
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

// 2. Fix the "Invalid Cache Path" error by forcing the storage path
// This ensures Laravel knows exactly where its folders are on DigitalOcean
$app->useStoragePath($app->basePath() . '/storage');

// 3. Manually set the view compiled path to avoid the missing config/view.php issue
config(['view.compiled' => realpath(storage_path('framework/views'))]);

return $app;
