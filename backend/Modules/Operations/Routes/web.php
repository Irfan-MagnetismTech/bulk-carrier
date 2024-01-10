<?php

use Illuminate\Support\Facades\Route;
use Modules\Operations\Http\Controllers\OpsVoyageReportController;
use Modules\Operations\Http\Controllers\OpsBulkNoonReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('operations')->group(function() {
    // Route::get('/', 'OperationsController@index'); 
});
// Route::resources([
//     'ports' => OpsPortController::class,
// ]);
// Route::get('lighter-voyage-report', [OpsVoyageReportController::class, 'lighterVoyageReport']);
// Route::get('bulk-report', [OpsBulkNoonReportController::class, 'showReport']);
