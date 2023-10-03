<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Operations\Http\Controllers\OpsPortController;
use Modules\Operations\Http\Controllers\OpsVesselController;
use Modules\Operations\Http\Controllers\OpsMaritimeCertificationController;
use Modules\Operations\Http\Controllers\OpsVesselCertificateController;
use Modules\Operations\Http\Controllers\OpsVesselParticularController;

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
Route::middleware(['auth:api'])->prefix('ops')->group(function ()
{
    // return $request->user();    
    Route::post('vessel-particulars/{vessel_particular}', [OpsVesselParticularController::class, 'update']);
    Route::resources([
        'ports' => OpsPortController::class,
        'vessels' => OpsVesselController::class,
        'maritime-certifications' => OpsMaritimeCertificationController::class,
        'vessel-certificates' => OpsVesselCertificateController::class,
        'vessel-particulars' => OpsVesselParticularController::class,
    ]);
});
Route::get('get/vessel/name', [OpsVesselController::class, 'getVesselName']);
Route::get('vessels/without/paginate', [OpsVesselController::class, 'getVesselWithoutPaginate']);
Route::post('vessel-search', [OpsVesselController::class, 'search']);