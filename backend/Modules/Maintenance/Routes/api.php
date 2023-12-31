<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Maintenance\Entities\MntCriticalFunction;
use Modules\Maintenance\Entities\MntCriticalItemCat;
use Modules\Maintenance\Entities\MntCriticalSpList;
use Modules\Maintenance\Entities\MntSurveyItem;
use Modules\Maintenance\Entities\MntSurveyType;
use Modules\Maintenance\Http\Controllers\MntCriticalFunctionController;
use Modules\Maintenance\Http\Controllers\MntCriticalItemCatController;
use Modules\Maintenance\Http\Controllers\MntCriticalItemController;
use Modules\Maintenance\Http\Controllers\MntCriticalSpListController;
use Modules\Maintenance\Http\Controllers\MntCriticalVesselItemController;
use Modules\Maintenance\Http\Controllers\MntItemController;
use Modules\Maintenance\Http\Controllers\MntItemGroupController;
use Modules\Maintenance\Http\Controllers\MntJobController;
use Modules\Maintenance\Http\Controllers\MntReportController;
use Modules\Maintenance\Http\Controllers\MntRunHourController;
use Modules\Maintenance\Http\Controllers\MntShipDepartmentController;
use Modules\Maintenance\Http\Controllers\MntSurveyController;
use Modules\Maintenance\Http\Controllers\MntSurveyItemController;
use Modules\Maintenance\Http\Controllers\MntSurveyTypeController;
use Modules\Maintenance\Http\Controllers\MntWorkRequisitionController;

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
        'work-requisitions' => MntWorkRequisitionController::class,
        'critical-functions' => MntCriticalFunctionController::class,
        'critical-item-cats' => MntCriticalItemCatController::class,
        'critical-items' => MntCriticalItemController::class,
        'critical-vessel-items' => MntCriticalVesselItemController::class,
        'critical-spare-lists' => MntCriticalSpListController::class,
        'survey-items' => MntSurveyItemController::class,
        'survey-types' => MntSurveyTypeController::class,
        'surveys' => MntSurveyController::class,
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
    // getJobsForRequisition
    Route::get('get-jobs-for-requisition', [MntJobController::class, 'getJobsForRequisition']);
    // indexWip
    Route::get('get-work-requisitions-wip', [MntWorkRequisitionController::class, 'indexWip']);
    // updateWip
    Route::put('update-work-requisition-wip/{id}', [MntWorkRequisitionController::class, 'updateWip']);
    // updateWipLine
    Route::put('update-work-requisition-line-wip/{id}', [MntWorkRequisitionController::class, 'updateWipLine']);
    // mntCriticalFunctions
    Route::get('get-critical-functions', [MntCriticalFunctionController::class, 'mntCriticalFunctions']);
    // getCriticalFunctionWiseItemCats
    Route::get('get-critical-item-cats', [MntCriticalItemCatController::class, 'getCriticalItemCats']);
    Route::get('get-critical-items', [MntCriticalItemController::class, 'getCriticalItems']);
    // getCriticalVesselItems
    Route::get('get-critical-vessel-items', [MntCriticalVesselItemController::class, 'getCriticalVesselItems']);
    // getCriticalVesselFunctions
    Route::get('get-critical-vessel-functions', [MntCriticalVesselItemController::class, 'getCriticalVesselFunctions']);
    // getSurveyItems 
    Route::get('get-survey-items', [MntSurveyItemController::class, 'mntSurveyItems']);
    // getSurveyTypes
    Route::get('get-survey-types', [MntSurveyTypeController::class, 'mntSurveyTypes']);
    // getSurveys
    Route::get('get-surveys', [MntSurveyController::class, 'mntSurveys']);

    // report all jobs
    Route::get('report-all-jobs', [MntReportController::class, 'reportAllJobs']);
    Route::get('report-upcoming-jobs', [MntReportController::class, 'reportUpcomingJobs']);
    Route::get('report-overdue-jobs', [MntReportController::class, 'reportOverdueJobs']);

});