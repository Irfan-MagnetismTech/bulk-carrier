<?php

namespace App\Providers;

use ReflectionClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use App\Support\Macros\CreateUpdateOrDelete;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        //relational method name case changing issue fix
        (new ReflectionClass(Model::class))->getProperty('snakeAttributes')->setValue(null, false);

        Response::macro('success', function ($message, $value, $statusCode = 200) {
            return response()->json([
                'message' => $message,
                'value' => $value,
            ], $statusCode);
        });

        // Response::macro('error', function ($error, $statusCode = 400) {
        //     return response()->json([
        //         'message' => 'Error: ' . $error,
        //     ], $statusCode);
        // });
        
        Response::macro('error', function ($error, $statusCode = 400) {
            throw new HttpResponseException(response()->json([
                'success'   => false,
                'message'   => 'Validation errors',
                'data'      => $error,
            ], 500));
        });

        HasMany::macro('createUpdateOrDelete', function (iterable $records) {
            /** @var HasMany */
            $hasMany = $this;

            return (new CreateUpdateOrDelete($hasMany, $records))();
        });
    }
}
