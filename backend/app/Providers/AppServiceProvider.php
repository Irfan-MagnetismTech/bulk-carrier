<?php

namespace App\Providers;

use App\Support\Macros\CreateUpdateOrDelete;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        Response::macro('success', function ($message, $value, $statusCode = 200) {
            return response()->json([
                'message' => $message,
                'value' => $value,
            ], $statusCode);
        });

        Response::macro('error', function ($error, $statusCode = 400) {
            return response()->json([
                'message' => 'Error: ' . $error,
            ], $statusCode);
        });

        HasMany::macro('createUpdateOrDelete', function (iterable $records) {
            /** @var HasMany */
            $hasMany = $this;
          
            return (new CreateUpdateOrDelete($hasMany, $records))();
          });
    }
}
