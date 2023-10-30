<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Maintenance\Http\Controllers\MntItemController;
use Modules\Maintenance\Http\Controllers\MntItemGroupController;
use Modules\Maintenance\Http\Controllers\MntJobController;
use Modules\Maintenance\Http\Controllers\MntRunHourController;
use Modules\Maintenance\Http\Controllers\MntShipDepartmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware(['auth:api'])->prefix('mnt')->as('mnt.')->group(function ()
{
    /* Common routes */
    Route::resources([
        'ship-departments' => MntShipDepartmentController::class,
        'item-groups' => MntItemGroupController::class,
        'items' => MntItemController::class,
        'jobs' => MntJobController::class,
        'run-hours' => MntRunHourController::class,
    ]);
    // get mnt ship departments without pagination
    Route::get('get-mnt-ship-departments', [MntShipDepartmentController::class, 'getMntShipDepartments']);
    // get mnt item groups without pagination
    Route::get('get-mnt-item-groups', [MntItemGroupController::class, 'getMntItemGroups']);
    //get Mnt Item Code
    Route::get('get-mnt-item-code/{mntItemGroupId}', [MntItemController::class, 'getMntItemCode']);
    //get mnt ship department wise items
    Route::get('get-mnt-ship-department-wise-items/{mntShipDepartmentId}', [MntItemController::class, 'getMntShipDepartmentWiseItems']);
    //get mnt item group wise items
    Route::get('get-mnt-item-group-wise-items/{mntItemGroupId}', [MntItemController::class, 'getMntItemGroupWiseItems']);
    //get mnt item group wise items which has run hour
    Route::get('get-mnt-item-group-wise-hourly-items/{mntItemGroupId}', [MntItemController::class, 'getMntItemGroupWiseHourlyItems']);
    //get mnt ship department wise item groups 
    Route::get('get-mnt-ship-department-wise-item-groups/{mntShipDepartmentId}', [MntItemGroupController::class, 'getMntShipDepartmentWiseItemGroups']);
    // get vessel item present hour
    Route::get('get-item-present-run-hour/{opsVesselId}/{mntItemId}', [MntJobController::class, 'getItemPresentRunHour']);
    // allJobs
    Route::get('get-all-jobs', [MntJobController::class, 'allJobs']);
    // upcoming Jobs
    Route::get('get-upcoming-jobs', [MntJobController::class, 'upcomingJobs']);
    // overDueJobs
    Route::get('get-overdue-jobs', [MntJobController::class, 'overDueJobs']);
    //vesselWiseJobs
    Route::get('get-vessel-wise-jobs', [MntJobController::class, 'vesselWiseJobs']);


});