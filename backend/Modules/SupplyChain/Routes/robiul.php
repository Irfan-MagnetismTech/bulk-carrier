<?php

use Illuminate\Support\Facades\Route;
use Modules\SupplyChain\Http\Controllers\ScmPoController;
use Modules\SupplyChain\Http\Controllers\ScmWoController;
use Modules\SupplyChain\Http\Controllers\ScmWrController;
use Modules\SupplyChain\Http\Controllers\ScmWcsController;
use Modules\SupplyChain\Http\Controllers\ScmWrrController;
use Modules\SupplyChain\Http\Controllers\ScmWcsqController;
use Modules\SupplyChain\Http\Controllers\ScmReportController;
use Modules\SupplyChain\Http\Controllers\ScmLcRecordStatusController;

Route::middleware(['auth:api'])->prefix('scm')->group(function () {
    //
    Route::apiResources([
        'work-requisitions' => ScmWrController::class,
        'work-cs' => ScmWcsController::class,
        'work-receipt-reports' => ScmWrrController::class,
        'work-orders' => ScmWoController::class,
        'lc-record-statuses' => ScmLcRecordStatusController::class,
    ]);

    Route::post('delete-lc-status', [ScmLcRecordStatusController::class, "lcStatusDelete"])->name('lcStatusDelete');
    Route::get('search-wr-wise-service-for-wcs', [ScmWrController::class, "getServiceByWrIdForWcs"])->name('getServiceByWrIdForWcs');
    Route::get('search-wr', [ScmWrController::class, "searchWr"])->name('searchWr');
    Route::get('search-work-requisitions', [ScmWrController::class, "searchWorkRequisitions"])->name('search-work-requisitions');
    Route::get('search-work-requisitions-for-wcs', [ScmWrController::class, "searchWorkRequisitionsForWcs"])->name('search-work-requisitions-wcs');
    Route::post('close-wr', [ScmWrController::class, "closeWr"])->name('closeWr');
    Route::post('close-wrline', [ScmWrController::class, "closeWrLine"])->name('closeWrLine');
    Route::get('work-requisitions-close', [ScmWrController::class, "wrCloseIndex"])->name('wrCloseIndex');


    Route::post('wcs-quotations', [ScmWcsqController::class, "store"])->name('wcs-quotations.create');
    Route::get('wcs-quotations/{quotationId}', [ScmWcsqController::class, "show"])->name('wcs-quotations.show');
    Route::get('wcs-quotations', [ScmWcsqController::class, "index"])->name('wcs-quotations.index');
    Route::put('wcs-quotations/{quotationId}', [ScmWcsqController::class, "update"])->name('wcs-quotations.update');
    Route::delete('wcs-quotations/{quotationId}', [ScmWcsqController::class, "delete"])->name('quotations.delete');
    Route::post('wcs-selected-supplier', [ScmWcsqController::class, "wcsSelectedSupplierstore"])->name('wcsselectedSupplier.store');

    Route::get('search-work-cs', [ScmWcsController::class, "searchWorkCs"])->name('searchWorkCs');
    Route::get('wcs-wise-vendor-list', [ScmWcsController::class, "wcsWiseVendorList"])->name('wcs-wise-vendor-list');
    Route::get('get-wcs-data/{id}', [ScmWcsController::class, "getWcsWiseData"])->name('getWcsWiseData');
    Route::get('getWcsData/{csId}', [ScmWcsController::class, "getWcsData"])->name('getWcsData');
    
    Route::get('get-po-material-by-po-id', [ScmPoController::class, "getPoMaterialByPoId"])->name('getPoMaterialByPoId');
    
    Route::get('get-wo-line-datas', [ScmWoController::class, "getWoLineDatas"])->name('get-wo-line-datas');
    Route::get('search-wr-wise-service', [ScmWoController::class, "getServiceByWrId"])->name('getServiceByWrId');
    Route::get('search-wo', [ScmWoController::class, "searchWo"])->name('searchWo');
    Route::get('get-wr-wcs-wise-wo-data', [ScmWoController::class, "getWoOrWoWcsWiseWrData"]);
    Route::get('search-wo-for-lc', [ScmWoController::class, "searchWoForLc"])->name('searchWoForLc');
    Route::post('close-wo', [ScmWoController::class, "closeWo"])->name('closeWo');
    Route::post('close-woitem', [ScmWoController::class, "closeWoItem"])->name('closeWoItem');
    Route::post('confirmation-wo', [ScmWoController::class, "confirmationWo"])->name('confirmationWo');
    Route::get('work-orders-close', [ScmWoController::class, "woCloseIndex"])->name('woCloseIndex');
    Route::get('get-wo-list', [ScmWoController::class, "getWoListForWrr"])->name('getWoListForWrr');
    Route::get('get-wo-wise-wr-list', [ScmWoController::class, "getWoWiseWrList"])->name('getWoWiseWrList');


    Route::get('search-wrr', [ScmWrrController::class, "searchWrr"])->name('searchWrr');
    Route::get('get-wrr-line-data', [ScmWrrController::class, "getWrrLineData"])->name('getWrrLineData');
    Route::get('get-wo-service-list', [ScmWrrController::class, "getWoServiceList"])->name('getWoServiceList');
    Route::get('get-service-for-wrr', [ScmWrrController::class, "getServiceByWrrId"])->name('getServiceForWrrId');
    
    Route::get('inventory-report', [ScmReportController::class, "inventoryReport"])->name('inventoryReport');
    Route::get('stock-history-report', [ScmReportController::class, "stockHistoryReport"])->name('stockHistoryReport');
    Route::get('purchase-requisition-report', [ScmReportController::class, "purchaseRequisitionReport"])->name('purchaseRequisitionReport');



});