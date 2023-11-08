<?php

use Illuminate\Support\Facades\Route;
use Modules\SupplyChain\Http\Controllers\ScmLcRecordController;
use Modules\SupplyChain\Http\Controllers\ScmUnitController;
use Modules\SupplyChain\Http\Controllers\ScmVendorController;
use Modules\SupplyChain\Http\Controllers\ScmMaterialController;
use Modules\SupplyChain\Http\Controllers\ScmWarehouseController;
use Modules\SupplyChain\Http\Controllers\ScmMaterialCategoryController;
use Modules\SupplyChain\Http\Controllers\ScmMrrController;
use Modules\SupplyChain\Http\Controllers\ScmOpeningStockController;
use Modules\SupplyChain\Http\Controllers\ScmPoController;
use Modules\SupplyChain\Http\Controllers\ScmPrController;
use Modules\SupplyChain\Http\Controllers\ScmServiceController;
use Modules\SupplyChain\Http\Controllers\ScmSrController;
use Modules\SupplyChain\Http\Controllers\ScmSiController;
use Modules\SupplyChain\Http\Controllers\ScmStockLedgerController;

Route::middleware('auth:api')->prefix('scm')->group(function () {
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
    ]);

    //Search Apis
    Route::get('search-material-category', [ScmMaterialCategoryController::class, "searchMaterialCategory"])->name('searchMaterialCategory');
    Route::get('search-unit', [ScmUnitController::class, "searchUnit"])->name('searchUnit');
    Route::get('search-materials', [ScmMaterialController::class, "searchMaterial"])->name('searchMaterial');
    Route::get('search-warehouse', [ScmWarehouseController::class, "searchWarehouse"])->name('searchWarehouse');
    Route::get('search-vendor', [ScmVendorController::class, "searchVendor"])->name('searchVendor');
    Route::get('search-pr-wise-material', [ScmPoController::class, "getMaterialByPrId"])->name('getMaterialByPrId');
    Route::get('search-po', [ScmPoController::class, "searchPo"])->name('searchPo');
    Route::get('search-lc-record', [ScmLcRecordController::class, "searchLcRecord"])->name('searchLcRecord');
    Route::get('search-materials-by-category', [ScmMaterialController::class, "searchMaterialByCategory"])->name('searchMaterialByCategory');
    Route::get('get-po-or-pr-wise-mrr', [ScmMrrController::class, "getPoOrPrWiseMrrData"])->name('getPoOrPrWiseMrrData');
    Route::get('get-sr-wise-data', [ScmSiController::class, "getSrWiseData"])->name('getSrWiseData');
    Route::get('search-mrr', [ScmMrrController::class, "searchMrr"])->name('searchMrr');
    Route::get('get-material-for-mrr', [ScmMrrController::class, "getMaterialByPrId"])->name('getMaterialByPrId');
    
    //Business Info Apis
    Route::get('store-categories', fn () => config('businessinfo.store_category'));
    Route::get('product-types', fn () => config('businessinfo.product_type'));
    Route::get('lc-cost-heads', fn () => config('businessinfo.lc_cost_heads'));

    //Laravel Excel Apis
    Route::get('export-materials', [ScmMaterialController::class, "export"])->name('exportMaterials');
    Route::get('import-materials', [ScmMaterialController::class, "import"])->name('importMaterials');

    //Current Stock Apis
    Route::get('current-stock-by-material', [ScmStockLedgerController::class, "currentStock"])->name('currentStock');
    Route::get('get-pr-cs-wise-po-data', [ScmPoController::class, "getPoOrPoCsWisePrData"]);
});
