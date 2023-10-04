<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Maintenance\Http\Controllers\MntItemController;
use Modules\Maintenance\Http\Controllers\MntItemGroupController;
use Modules\Maintenance\Http\Controllers\MntShipDepartmentController;

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
Route::middleware(['auth:api'])->prefix('mnt')->as('mnt.')->group(function ()
{
    /* Common routes */
    Route::resources([
        'ship-departments' => MntShipDepartmentController::class,
        'item-groups' => MntItemGroupController::class,
        'items' => MntItemController::class,
    ]);
    // get mnt ship departments without pagination
    Route::get('get-mnt-ship-departments', [MntShipDepartmentController::class, 'getMntShipDepartments']);
    // get mnt item groups without pagination
    Route::get('get-mnt-item-groups', [MntItemGroupController::class, 'getMntItemGroups']);
});