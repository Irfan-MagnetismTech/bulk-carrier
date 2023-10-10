<?php

namespace App\Providers;

use ReflectionClass;
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
        
        // Register the HasBusinessUnit trait as a global scope for all models
        foreach (glob(app_path('Modules/*/Models/*.php')) as $file) {
            $modelClass = 'App\\' . str_replace('/', '\\', substr(dirname($file), strlen(app_path()) + 1)) . '\\' . basename($file, '.php');
            if (is_subclass_of($modelClass, 'Illuminate\\Database\\Eloquent\\Model')) {
                $modelClass::addGlobalScope(new \App\Traits\CreateBusinessUnit);
            }
        }

        //relational method name case changing issue fix
        (new ReflectionClass(Model::class))->getProperty('snakeAttributes')->setValue(null, false);

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
    }
}
