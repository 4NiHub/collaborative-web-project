<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
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
