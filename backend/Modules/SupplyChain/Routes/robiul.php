<?php

use Illuminate\Support\Facades\Route;
use Modules\SupplyChain\Http\Controllers\ScmWrController;
use Modules\SupplyChain\Http\Controllers\ScmWcsController;

Route::middleware(['auth:api'])->prefix('scm')->group(function () {
    //
    Route::apiResources([
        'work-requisitions' => ScmWrController::class,
        'work-cs' => ScmWcsController::class,
    ]);

    Route::get('search-wr-wise-service-for-wcs', [ScmWrController::class, "getServiceByWrIdForWcs"])->name('getServiceByWrIdForWcs');
    Route::get('search-wr', [ScmWrController::class, "searchWr"])->name('searchWr');
    Route::get('search-work-requisitions', [ScmWrController::class, "searchWorkRequisitions"])->name('search-work-requisitions');
    Route::post('wcs-quotations', [ScmWcsController::class, "storeQuotation"])->name('wcs-quotations.create');
    Route::get('wcs-quotations/{quotationId}', [ScmWcsController::class, "showQuotation"])->name('wcs-quotations.show');
    Route::get('wcs-quotations', [ScmWcsController::class, "getWorkQuotations"])->name('wcs-quotations.index');
    Route::get('get-wcs-data/{id}', [ScmWcsController::class, "getWcsWiseData"])->name('getWcsWiseData');
    Route::post('close-wr', [ScmWrController::class, "closeWr"])->name('closeWr');
    Route::post('close-wrline', [ScmWrController::class, "closeWrLine"])->name('closeWrLine');
});