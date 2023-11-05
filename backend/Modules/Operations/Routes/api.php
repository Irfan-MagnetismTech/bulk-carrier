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
use Modules\Operations\Http\Controllers\OpsChartererProfileController;
use Modules\Operations\Http\Controllers\OpsChartererContractController;
use Modules\Operations\Http\Controllers\OpsHandoverTakeoverController;
use Modules\Operations\Http\Controllers\OpsChartererInvoiceController;
use Modules\Operations\Http\Controllers\OpsLighterNoonReportController;
use Modules\Operations\Http\Controllers\OpsCommonController;

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
        'charterer-profiles' => OpsChartererProfileController::class,
        'charterer-contracts' => OpsChartererContractController::class,
        'handover-takeovers' => OpsHandoverTakeoverController::class,
        'charterer-invoices' => OpsChartererInvoiceController::class,
        'lighter-noon-reports' => OpsLighterNoonReportController::class,
    ]);

    //start for without pagination
    Route::get('get-ports', [OpsCommonController::class, 'getPortWithoutPaginate']);
    Route::get('get-vessels', [OpsCommonController::class, 'getVesselWithoutPaginate']);
    Route::get('get-cargo-types', [OpsCommonController::class, 'getCargoTypeWithoutPaginate']);
    Route::get('get-cargo-tariffs', [OpsCommonController::class, 'getCargoTariffWithoutPaginate']);
    Route::get('get-customers', [OpsCommonController::class, 'getCustomerWithoutPaginate']);
    Route::get('get-maritime-certifications', [OpsCommonController::class, 'getMaritimeCertificationWithoutPaginate']);
    Route::get('get-vessel-certificates', [OpsCommonController::class, 'getVesselCertificateWithoutPaginate']);
    Route::get('get-vessel-particulars', [OpsCommonController::class, 'getVesselParticularWithoutPaginate']);
    Route::get('get-voyages', [OpsCommonController::class, 'getVoyageWithoutPaginate']);
    Route::get('get-voyage-boat-notes', [OpsCommonController::class, 'getVoyageBoatNoteWithoutPaginate']);
    Route::get('get-charterer-profiles', [OpsCommonController::class, 'getCargoProfileWithoutPaginate']);
    Route::get('get-charterer-contracts', [OpsCommonController::class, 'getChartererContractWithoutPaginate']);
    Route::get('get-handover-takeovers', [OpsCommonController::class, 'getHandoverTakeoverWithoutPaginate']);
    Route::get('get-charterer-invoices', [OpsCommonController::class, 'getChartererInvoiceWithoutPaginate']);
    Route::get('get-lighter-noon-reports', [OpsCommonController::class, 'getLighterNoonReportWithoutPaginate']);
    //end for without pagination

    // start for search api route
    Route::get('search-ports', [OpsPortController::class, 'getPortByNameOrCode']);
    Route::get('search-cargo-types', [OpsCargoTypeController::class, 'getCargoTypeByName']);
    Route::get('search-cargo-tariffs', [OpsCargoTariffController::class, 'getCargoTariffByName']);
    Route::get('search-vessels', [OpsVesselController::class, 'getVesselByNameorCode']);
    Route::get('search-maritime-certifications', [OpsMaritimeCertificationController::class, 'getMaritimeCertificationByName']);
    Route::get('search-vessel-certificates', [OpsVesselCertificateController::class, 'getVesselCertificateByReferenceNumber']);
    Route::get('search-customers', [OpsCustomerController::class, 'getCustomerByNameorCode']);
    Route::get('search-voyages', [OpsVoyageController::class, 'getVoyageByVoyageNo']);
    Route::get('search-charterer-profiles', [OpsChartererProfileController::class, 'getChartererProfileByNameorCode']);
    
    // end for search api route
    Route::get('search-vessels-latest', [OpsVesselController::class, 'getVesselLatest']);
    Route::get('vessel-certificate-history', [OpsVesselController::class, 'getVesselCertificateHistory']);
    Route::get('vessel-certificates-renew', [OpsVesselCertificateController::class, 'getIndexRenew']);
    
        
    // report routes
    Route::get('vessel-particular-report', [OpsVesselParticularController::class, 'exportVesselParticularReport']);
    
});
Route::post('vessel-search', [OpsVesselController::class, 'search']);