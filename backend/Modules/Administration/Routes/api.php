<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Administration\Http\Controllers\PermissionController;
use Modules\Administration\Http\Controllers\RoleController;
use Modules\Administration\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:api'])->prefix('administration')->as('administration.')->group(function ()
{
    /* Common routes */
    Route::resource('users', UserController::class);
    Route::get('get-current-user', [UserController::class, 'getCurrentUser']);

    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
});
