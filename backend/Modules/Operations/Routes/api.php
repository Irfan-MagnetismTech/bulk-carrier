<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Operations\Http\Controllers\OpsPortController;
use Modules\Operations\Http\Controllers\OpsVesselController;

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
Route::middleware(['auth:api'])->prefix('operations')->as('operations.')->group(function ()
{
    // return $request->user();
    
});
Route::resources([
    'ports' => OpsPortController::class,
    'vessels' => OpsVesselController::class,
    
]);
Route::get('get/vessel/name', [OpsVesselController::class, 'getVesselName']);
Route::get('vessels/without/paginate', [OpsVesselController::class, 'getVesselWithoutPaginate']);
Route::post('vessel-search', [OpsVesselController::class, 'search']);