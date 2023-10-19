<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix('scm')->group(function () {
    // Route::prefix('scm')->group(function () {
    Route::apiResources([
        // 'material-categories' => ScmMaterialCategoryController::class,
        'units' => ScmUnitController::class,
        // 'materials' => ScmMaterialController::class,
        // 'wearhouses' => ScmWearhouseController::class,
        // 'vendors' => ScmVendorController::class
    ]);
    // Route::get('search-material-category',[ScmMaterialCategoryController::class,"searchMaterialCategory"])->name('searchMaterialCategory');
});
