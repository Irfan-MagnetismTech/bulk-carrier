<?php

use Illuminate\Support\Facades\Route;
use Modules\SupplyChain\Http\Controllers\ScmUnitController;
use Modules\SupplyChain\Http\Controllers\ScmVendorController;
use Modules\SupplyChain\Http\Controllers\ScmMaterialController;
use Modules\SupplyChain\Http\Controllers\ScmWarehouseController;
use Modules\SupplyChain\Http\Controllers\ScmMaterialCategoryController;
use Modules\SupplyChain\Http\Controllers\ScmOpeningStockController;
use Modules\SupplyChain\Http\Controllers\ScmPrController;
use Modules\SupplyChain\Http\Controllers\ScmServiceController;

Route::middleware('auth:api')->prefix('scm')->group(function () {
    // Route::prefix('scm')->group(function () {
    Route::apiResources([
        'material-categories' => ScmMaterialCategoryController::class,
        'units' => ScmUnitController::class,
        'materials' => ScmMaterialController::class,
        'vendors' => ScmVendorController::class,
        'warehouses' => ScmWarehouseController::class,
        'opening-stocks' => ScmOpeningStockController::class,
        'services' => ScmServiceController::class,
        'purchase-requisitions' => ScmPrController::class,
    ]);
    Route::get('search-material-category', [ScmMaterialCategoryController::class, "searchMaterialCategory"])->name('searchMaterialCategory');
    Route::get('search-unit', [ScmUnitController::class, "searchUnit"])->name('searchUnit');
    Route::get('search-materials', [ScmMaterialController::class, "searchMaterial"])->name('searchMaterial');
    Route::get('search-warehouse', [ScmWarehouseController::class, "searchWarehouse"])->name('searchWarehouse');
    Route::get('search-materials-by-category', [ScmMaterialController::class, "searchMaterialByCategory"])->name('searchMaterialByCategory');
    Route::get('store-categories', fn () => config('businessinfo.store_category'));
    Route::get('export-materials', [ScmMaterialController::class, "export"])->name('exportMaterials');
    Route::get('import-materials', [ScmMaterialController::class, "import"])->name('exportMaterials');
});
