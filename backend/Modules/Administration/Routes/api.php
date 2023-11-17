<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Administration\Http\Controllers\PermissionController;
use Modules\Administration\Http\Controllers\RoleController;
use Modules\Administration\Http\Controllers\UserController;
use Modules\Administration\Http\Controllers\AdministrationCommonController;

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

Route::middleware(['auth:api'])->prefix('administration')->group(function ()
{
    /* Common routes */
    Route::resource('users', UserController::class);
    Route::get('get-current-user', [UserController::class, 'getCurrentUser']);

    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::get('permissions-group-by-subject', [PermissionController::class, 'getPermissionsGroupBySubject']);

    //helper apis
    Route::post('get-administration-users', [AdministrationCommonController::class, 'getUsers']);
    Route::post('get-administration-roles', [AdministrationCommonController::class, 'getRoles']);
    Route::post('get-administration-permissions', [AdministrationCommonController::class, 'getPermissions']);
});
