<?php

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

use Illuminate\Support\Facades\Route;
use Modules\Maintenance\Http\Controllers\MntReportController;

Route::prefix('mnt')->group(function() {
    Route::get('/', 'MaintenanceController@index');
    Route::get('/get-pdf', [MntReportController::class, 'getPdfAllJobs']);
});
