<?php

use Illuminate\Support\Facades\Route;
use Modules\SupplyChain\Http\Controllers\ScmWrController;

Route::middleware(['auth:api'])->prefix('scm')->group(function () {
    //
    Route::apiResources([
        'work-requisitions' => ScmWrController::class,
    ]);

    Route::post('close-wr', [ScmWrController::class, "closeWr"])->name('closeWr');
    // Route::post('close-wrline', [ScmWrController::class, "closeWrLine"])->name('closeWrLine');
});