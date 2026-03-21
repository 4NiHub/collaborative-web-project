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
        // Change this to check if it's NOT local, 
        // or just force it if you are sure your DO droplet uses SSL
        if (config('app.env') === 'production' || config('app.env') === 'staging') {
            URL::forceScheme('https');
        }

        if (app()->environment('local')) {
            Response::macro('apiJson', function ($data = [], $status = 200) {
                return response()->json($data, $status)
                    ->header('Content-Type', 'application/json; charset=utf-8');
            });
        }
    }
}
