<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Maintenance\Http\Controllers\ShipDepartmentController;

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
    Route::resource('ship-departments', ShipDepartmentController::class);
});