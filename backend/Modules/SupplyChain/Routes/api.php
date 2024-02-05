<?php

use Illuminate\Support\Facades\Route;
use Modules\SupplyChain\Http\Controllers\ScmCsController;
use Modules\SupplyChain\Http\Controllers\ScmMiController;
use Modules\SupplyChain\Http\Controllers\ScmMoController;
use Modules\SupplyChain\Http\Controllers\ScmPoController;
use Modules\SupplyChain\Http\Controllers\ScmPrController;
use Modules\SupplyChain\Http\Controllers\ScmSiController;
use Modules\SupplyChain\Http\Controllers\ScmSrController;
use Modules\SupplyChain\Http\Controllers\ScmMmrController;
use Modules\SupplyChain\Http\Controllers\ScmMrrController;
use Modules\SupplyChain\Http\Controllers\ScmSirController;
use Modules\SupplyChain\Http\Controllers\ScmUnitController;
use Modules\SupplyChain\Http\Controllers\ScmVendorController;
use Modules\SupplyChain\Http\Controllers\ScmServiceController;
use Modules\SupplyChain\Http\Controllers\ScmLcRecordController;
use Modules\SupplyChain\Http\Controllers\ScmMaterialController;
use Modules\SupplyChain\Http\Controllers\SupplyChainController;
use Modules\SupplyChain\Http\Controllers\ScmWarehouseController;
use Modules\SupplyChain\Http\Controllers\ScmAdjustmentController;
use Modules\SupplyChain\Http\Controllers\ScmStockLedgerController;
use Modules\SupplyChain\Http\Controllers\ScmOpeningStockController;
use Modules\SupplyChain\Http\Controllers\ScmMaterialCategoryController;

Route::middleware(['auth:api'])->prefix('scm')->group(function () {
    Route::apiResources([
        'material-categories' => ScmMaterialCategoryController::class,
        'units' => ScmUnitController::class,
        'materials' => ScmMaterialController::class,
        'vendors' => ScmVendorController::class,
        'warehouses' => ScmWarehouseController::class,
        'opening-stocks' => ScmOpeningStockController::class,
        'services' => ScmServiceController::class,
        'purchase-requisitions' => ScmPrController::class,
        'purchase-orders' => ScmPoController::class,
        'lc-records' => ScmLcRecordController::class,
        'material-receipt-reports' => ScmMrrController::class,
        'store-requisitions' => ScmSrController::class,
        'store-issues' => ScmSiController::class,
        'store-issue-returns' => ScmSirController::class,
        'movement-requisitions' => ScmMmrController::class,
        'movement-outs' => ScmMoController::class,
        'movement-ins' => ScmMiController::class,
        'material-cs' => ScmCsController::class,
        'adjustments' => ScmAdjustmentController::class,
    ]);

    //Search Apis
    Route::get('search-material-category', [ScmMaterialCategoryController::class, "searchMaterialCategory"])->name('searchMaterialCategory');
    Route::get('search-unit', [ScmUnitController::class, "searchUnit"])->name('searchUnit');

    Route::get('search-services', [ScmServiceController::class, "searchService"])->name('searchService');
    Route::get('search-warehouse', [ScmWarehouseController::class, "searchWarehouse"])->name('searchWarehouse');
    Route::get('search-vendor', [ScmVendorController::class, "searchVendor"])->name('searchVendor');


    Route::get('search-sr-wise-material', [ScmSrController::class, "getMaterialBySrId"])->name('getMaterialBySrId');
    Route::get('get-si-wise-materials', [ScmSiController::class, "getMaterialBySiId"])->name('getMaterialBySiId');
    Route::get('get-mmr-wise-materials', [ScmMmrController::class, "getMaterialByMmrId"])->name('getMaterialByMmrId');


    Route::get('search-lc-record', [ScmLcRecordController::class, "searchLcRecord"])->name('searchLcRecord');
    Route::get('search-store-issue', [ScmSiController::class, "searchStoreIssue"])->name('searchStoreIssue');

    Route::get('get-po-or-pr-wise-mrr', [ScmMrrController::class, "getPoOrPrWiseMrrData"])->name('getPoOrPrWiseMrrData');
    Route::get('get-sr-wise-data', [ScmSiController::class, "getSrWiseData"])->name('getSrWiseData');
    Route::get('get-si-wise-data', [ScmSirController::class, "getSiWiseData"])->name('getSiWiseData');
    Route::get('get-mmr-wise-data', [ScmMmrController::class, "getMmrWiseData"])->name('getMmrWiseData');
    Route::get('get-mmr-wise-mi-data', [ScmMiController::class, "getMmrWiseMiData"])->name('getMmrWiseMiData');
    Route::get('get-mo-wise-mi-data', [ScmMiController::class, "getMoWiseMiData"])->name('getMoWiseMiData');

    Route::get('search-mrr', [ScmMrrController::class, "searchMrr"])->name('searchMrr');





    Route::get('search-mmr', [ScmMmrController::class, "searchMmr"])->name('searchMmr');
    Route::get('search-mo', [ScmMoController::class, "searchMo"])->name('searchMo');

    Route::get('get-material-for-mrr', [ScmMrrController::class, "getMaterialByPrId"])->name('getMaterialForMrrId');
    Route::get('get-mrr-line-data', [ScmMrrController::class, "getMrrLineData"])->name('getMrrLineData');
    Route::get('get-po-material-list', [ScmMrrController::class, "getPoMaterialList"])->name('getPoMaterialList');
    Route::get('get-current-stock-by-warehouse', [ScmMmrController::class, "getCurrentStockByWarehouse"])->name('getCurrentStockByWarehouse');




    //Business Info Apis
    Route::get('store-categories', fn () => config('businessinfo.store_category'));
    Route::get('product-types', fn () => config('businessinfo.product_type'));
    Route::get('lc-cost-heads', fn () => config('businessinfo.lc_cost_heads'));

    //Laravel Excel Apis



    //Current Stock Apis
    Route::get('current-stock-by-material', [ScmStockLedgerController::class, "currentStock"])->name('currentStock');

    Route::get('stock', [SupplyChainController::class, "getCurrentStock"]);

    Route::controller(ScmMaterialController::class)->group(function () {
        Route::get('search-materials', "searchMaterial")->name('searchMaterial');
        Route::get('search-materials-by-category', "searchMaterialByCategory")->name('searchMaterialByCategory');
        Route::get('export-materials', "export")->name('exportMaterials');
        Route::get('import-materials', "import")->name('importMaterials');
    });

    Route::controller(ScmPrController::class)->group(function () {
        Route::get('search-pr-wise-material-for-cs', "getMaterialByPrIdForCs")->name('getMaterialByPrIdForCs');
        Route::get('get-pr-wise-cs-data', "getPrWiseCsData")->name('getPrWiseCsData');
        Route::get('search-pr', "searchPr")->name('searchPr');
        Route::get('search-purchase-requisitions', "searchPurchaseRequisitions")->name('search-purchase-requisitions');
        Route::get('search-purchase-requisitions-for-cs', "searchPurchaseRequisitionsForCs")->name('search-purchase-requisitions-for-cs');
        Route::post('close-pr', "closePr")->name('closePr');
        Route::post('close-prline', "closePrLine")->name('closePrLine');
    });

    Route::controller(ScmCsController::class)->group(function () {
        Route::post('store-cs-landed-cost', "storeCsLandedCost")->name('storeCsLandedCost');
        Route::post('update-cs-landed-cost', "updateCsLandedCost")->name('updateCsLandedCost');
        Route::get('get-cs-data/{id}', "getCsWiseData")->name('getCsWiseData');

        // CS quotation
        Route::post('quotations', "storeQuotation")->name('quotations.create');
        Route::get('quotations', "getQuotations")->name('quotations.index');
        Route::get('quotations/{quotationId}', "showQuotation")->name('quotations.show');
        Route::put('quotations/{quotationId}', "updateQuotation")->name('quotations.update');
        Route::delete('quotations/{quotationId}', "deleteQuotation")->name('quotations.delete');

        Route::get('cs-wise-vendor-list', "csWiseVendorList")->name('cs-wise-vendor-list');
        Route::get('search-material-cs', "searchMaterialCs")->name('searchMaterialCs');
        Route::get('getCsData/{csId}', "getCsData")->name('getCsData');
        Route::post('selected-supplier', "selectedSupplierstore")->name('selectedSupplier.store');
        Route::get('selected-vendors', "selectedVendors")->name('selectedVendors');
    });

    Route::controller(ScmPoController::class)->group(function () {
        Route::get('search-pr-wise-material', "getMaterialByPrId")->name('getMaterialByPrId');
        Route::get('search-po', "searchPo")->name('searchPo');
        Route::get('get-po-list', "getPoListForMrr")->name('getPoListForMrr');
        Route::get('search-po-for-lc', "searchPoForLc")->name('searchPoForLc');
        Route::get('get-po-line-datas', "getPoLineDatas")->name('get-po-line-datas');
        Route::get('get-pr-cs-wise-po-data', "getPoOrPoCsWisePrData");
        Route::post('close-po', "closePo")->name('closePo');
        Route::post('close-poline', "closePoLine")->name('closePoLine');
        Route::get('get-po-wise-pr-list', "getPoWisePrList")->name('getPoWisePrList');
    });
});

require __DIR__ . '/robiul.php';
