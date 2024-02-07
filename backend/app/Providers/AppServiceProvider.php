<?php

namespace App\Providers;

use ReflectionClass;
use App\Observers\GlobalObserver;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
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

        Response::macro('success', function ($message = "data", $value, $statusCode = 200) {
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
                'message'   => 'errors',
                'data'      => $error,
            ], $statusCode));
        });

        HasMany::macro('createUpdateOrDelete', function (iterable $records) {
            /** @var HasMany */
            $hasMany = $this;

            return (new CreateUpdateOrDelete($hasMany, $records))();
        });

        if (! app()->environment('production')) {
            Mail::alwaysTo('sumon@magnetismtech.com');
        }

        // Register the Global Observer for all the models
        $directories = [
            base_path('App/Models'), // Laravel's default model location
        ];

        // Get the directories of the Entities in each enabled module
        $modules = Module::allEnabled();
        foreach ($modules as $module) {
            $directories[] = $module->getPath() . '/Entities';
        }

        foreach ($directories as $directory) {
            if (File::isDirectory($directory)) {
                $models = collect(File::allFiles($directory))
                    ->map(function ($item) use ($directory) {
                        // Get the namespace of the models based on the directory
                        $namespace = str_replace('/', '\\', str_replace(base_path(), '', $directory) . '\\');
                        return $namespace . $item->getBasename('.php');
                    });

                foreach ($models as $model) {
                    $model::observe(GlobalObserver::class);
                }
            }
        }
    }
}
