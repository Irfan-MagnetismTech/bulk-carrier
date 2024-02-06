<?php

use Illuminate\Support\Facades\Route;
use Modules\Administration\Http\Controllers\UserController;
use Modules\Maintenance\Http\Controllers\MntReportController;
use Modules\Maintenance\Http\Controllers\MntShipDepartmentController;
use Modules\Operations\Http\Controllers\OpsBunkerReportController;
use Modules\Operations\Http\Controllers\OpsExpenseReportController;
use Modules\Operations\Http\Controllers\OpsVoyageReportController;
use Modules\Operations\Http\Controllers\OpsOperationReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('login', [UserController::class, 'index']);
// Route::get('/test', fn()=> 'hi');
Route::get('/', fn() => 'BG Torony - Magnetism Tech Limited');
Route::get('/get-pdf', [MntReportController::class, 'getPdfAllJobs']);
