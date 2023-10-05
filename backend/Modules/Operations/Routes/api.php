<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Operations\Http\Controllers\OpsPortController;
use Modules\Operations\Http\Controllers\OpsVesselController;
use Modules\Operations\Http\Controllers\OpsMaritimeCertificationController;
use Modules\Operations\Http\Controllers\OpsVesselCertificateController;
use Modules\Operations\Http\Controllers\OpsVesselParticularController;
use Modules\Operations\Http\Controllers\OpsCargoTypeController;
use Modules\Operations\Http\Controllers\OpsCargoTariffController;
use Modules\Operations\Http\Controllers\OpsCustomerController;
use Modules\Operations\Http\Controllers\OpsVoyageController;
use Modules\Operations\Http\Controllers\OpsVoyageBoatNoteController;

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
    Route::resources([
        'ports' => OpsPortController::class,
        'vessels' => OpsVesselController::class,
        'cargo-types' => OpsCargoTypeController::class,
        'cargo-tariffs' => OpsCargoTariffController::class,
        'customers' => OpsCustomerController::class,
        'maritime-certifications' => OpsMaritimeCertificationController::class,
        'vessel-certificates' => OpsVesselCertificateController::class,
        'vessel-particulars' => OpsVesselParticularController::class,
        'voyages' => OpsVoyageController::class,
        'voyage-boat-notes' => OpsVoyageBoatNoteController::class,
    ]);

    //start for without pagination
    Route::get('ports/without/paginate', [OpsPortController::class, 'getPortWithoutPaginate']);
    Route::get('vessels/without/paginate', [OpsVesselController::class, 'getVesselWithoutPaginate']);
    Route::get('cargo-types/without/paginate', [OpsCargoTypeController::class, 'getCargoTypeWithoutPaginate']);
    Route::get('cargo-tariffs/without/paginate', [OpsCargoTariffController::class, 'getCargoTariffWithoutPaginate']);
    Route::get('customers/without/paginate', [OpsCustomerController::class, 'getCustomerWithoutPaginate']);
    Route::get('maritime-certifications/without/paginate', [OpsMaritimeCertificationController::class, 'getMaritimeCertificationWithoutPaginate']);
    Route::get('vessel-certificates/without/paginate', [OpsVesselCertificateController::class, 'getVesselCertificateWithoutPaginate']);
    Route::get('vessel-particulars/without/paginate', [OpsVesselParticularController::class, 'getVesselParticularWithoutPaginate']);
    Route::get('voyages/without/paginate', [OpsVoyageController::class, 'getVoyageWithoutPaginate']);
    Route::get('voyage-boat-notes/without/paginate', [OpsVoyageBoatNoteController::class, 'getVoyageBoatNoteWithoutPaginate']);
    //end for without pagination
});
Route::get('get/vessel/name', [OpsVesselController::class, 'getVesselName']);
Route::post('vessel-search', [OpsVesselController::class, 'search']);