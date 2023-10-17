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
use Modules\Operations\Http\Controllers\OpsCustomerInvoiceController;

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
        'customer-invoices' => OpsCustomerInvoiceController::class,
    ]);

    //start for without pagination
    Route::get('ports/without/paginate', [OpsCommonController::class, 'getPortWithoutPaginate']);
    Route::get('vessels/without/paginate', [OpsCommonController::class, 'getVesselWithoutPaginate']);
    Route::get('cargo-types/without/paginate', [OpsCommonController::class, 'getCargoTypeWithoutPaginate']);
    Route::get('cargo-tariffs/without/paginate', [OpsCommonController::class, 'getCargoTariffWithoutPaginate']);
    Route::get('customers/without/paginate', [OpsCommonController::class, 'getCustomerWithoutPaginate']);
    Route::get('maritime-certifications/without/paginate', [OpsCommonController::class, 'getMaritimeCertificationWithoutPaginate']);
    Route::get('vessel-certificates/without/paginate', [OpsCommonController::class, 'getVesselCertificateWithoutPaginate']);
    Route::get('vessel-particulars/without/paginate', [OpsCommonController::class, 'getVesselParticularWithoutPaginate']);
    Route::get('voyages/without/paginate', [OpsCommonController::class, 'getVoyageWithoutPaginate']);
    Route::get('voyage-boat-notes/without/paginate', [OpsCommonController::class, 'getVoyageBoatNoteWithoutPaginate']);
    Route::get('charterer-profiles/without/paginate', [OpsCommonController::class, 'getChartererProfileWithoutPaginate']);
    Route::get('charterer-contracts/without/paginate', [OpsCommonController::class, 'getChartererContractWithoutPaginate']);
    Route::get('handover-takeovers/without/paginate', [OpsCommonController::class, 'getHandoverTakeoverWithoutPaginate']);
    Route::get('charterer-invoices/without/paginate', [OpsCommonController::class, 'getChartererInvoiceWithoutPaginate']);
    Route::get('lighter-noon-reports/without/paginate', [OpsCommonController::class, 'getLighterNoonReportWithoutPaginate']);
    Route::get('customer-invoices/without/paginate', [OpsCommonController::class, 'getCustomerInvoiceWithoutPaginate']);
    //end for without pagination

    // start for search api route
    Route::get('search-ports', [OpsPortController::class, 'getPortByNameOrCode']);
    Route::get('search-cargo-types', [OpsCargoTypeController::class, 'getCargoTypeByName']);
    Route::get('search-cargo-tariffs', [OpsCargoTariffController::class, 'getCargoTariffByName']);
    Route::get('search-vessels', [OpsVesselController::class, 'getVesselByName']);
    Route::get('search-maritime-certifications', [OpsMaritimeCertificationController::class, 'getMaritimeCertificationByName']);
    Route::get('search-vessel-certificates', [OpsVesselCertificateController::class, 'getVesselCertificateByReferenceNumber']);
    Route::get('search-customers', [OpsCustomerController::class, 'getCustomerByNameorCode']);
    Route::get('search-voyages', [OpsVoyageController::class, 'getVoyageByVoyageNo']);
    Route::get('search-charterer-profiles', [OpsChartererProfileController::class, 'getChartererProfileByNameorCode']);
    
    // end for search api route

});
Route::post('vessel-search', [OpsVesselController::class, 'search']);