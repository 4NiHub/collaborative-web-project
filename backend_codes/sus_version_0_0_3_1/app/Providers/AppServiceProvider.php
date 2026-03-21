<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL; // <--- MUST ADD THIS

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Force HTTPS on DigitalOcean/Production
        if (config('app.env') === 'production' || config('app.env') === 'staging') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // 2. Register the macro for ALL environments (so it doesn't crash on DO)
        \Illuminate\Support\Facades\Response::macro('apiJson', function ($data = [], $status = 200) {
            return response()->json($data, $status)
                ->header('Content-Type', 'application/json; charset=utf-8');
        });
    }
}
