<?php

namespace App\Exceptions;

use Error;
use Exception;
use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        /**
         * Handle the Authentication exception
         */
        // $this->renderable(function (QueryException $e) {
        //     $errorMessage = $e->getMessage();

        //     if (app()->environment('production')) {
        //         switch ($e->errorInfo[1]) {
        //             case 1366:
        //                 $errorMessage = 'Data type is not matching for some columns!';
        //                 break;

        //             case 3730:
        //                 $errorMessage = 'Data is already used in another section!';
        //                 break;

        //             case 1451:
        //                 $errorMessage = 'Related data is used in another section!';
        //                 break;
        //             case 1264:
        //                 $errorMessage = 'Invaled number range!';
        //                 break;
        //         }
        //     }

        //     return response()->error($errorMessage, 500);
        // });

        /**
         * Handle the validation exception
         */
        // $this->renderable(fn (ValidationException $e)  => response()->error($e->getMessage(), 406));

        /**
         * Handle the Authentication exception
         */
        // $this->renderable(fn (AuthenticationException $e) => response()->error($e->getMessage(), 401));

        /**
         * Handle all exceptions
         */
        // $this->renderable(fn (Exception $e) => response()->error($e->getMessage(), 200));

        /**
         * Handle the error thrown by the application.
         */
        // $this->renderable(fn (Error $e, $request) => response()->error($e->getMessage(), 500));
    }
}
