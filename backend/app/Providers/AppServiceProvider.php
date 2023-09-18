<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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
        Response::macro('success', function ($value, $statusCode = 200) {
            return response()->json([
                'status' => 'success',
                'value' => $value,
            ], $statusCode);
        });

        Response::macro('error', function ($error, $statusCode = 400) {
            return response()->json([
                'message' => 'Error: ' . $error,
            ], $statusCode);
        });
    }
}
