<?php

use Illuminate\Support\Facades\Route;
use Modules\Operations\Http\Controllers\OpsBunkerReportController;
use Modules\Operations\Http\Controllers\OpsExpenseReportController;

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

Route::get('/single-vessel-wise-bunker-report', [OpsExpenseReportController::class, 'singleVesselWiseBunkerReport']);
Route::get('/business-unit-wise-bunker-report', [OpsBunkerReportController::class, 'businessUnitWiseBunkerReport']);
Route::get('/vessel-bunker-report', [OpsBunkerReportController::class, 'vesselBunkerReport']);
