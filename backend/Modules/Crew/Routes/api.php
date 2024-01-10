<?php

use Illuminate\Support\Facades\Route;
use Modules\Crew\Entities\CrwCrewDocumentRenewal;
use Modules\Crew\Http\Controllers\AppraisalFormController;
use Modules\Crew\Http\Controllers\AppraisalRecordController;
use Modules\Crew\Http\Controllers\CrwAgencyBillController;
use Modules\Crew\Http\Controllers\CrwAgencyContractController;
use Modules\Crew\Http\Controllers\CrwAgencyController;
use Modules\Crew\Http\Controllers\CrwAttendanceController;
use Modules\Crew\Http\Controllers\CrwBankAccountController;
use Modules\Crew\Http\Controllers\CrwCommonController;
use Modules\Crew\Http\Controllers\CrwCrewAssignmentController;
use Modules\Crew\Http\Controllers\CrwCrewChecklistController;
use Modules\Crew\Http\Controllers\CrwCrewController;
use Modules\Crew\Http\Controllers\CrwCrewDocumentController;
use Modules\Crew\Http\Controllers\CrwCrewDocumentRenewalController;
use Modules\Crew\Http\Controllers\CrwCrewProfileController;
use Modules\Crew\Http\Controllers\CrwCrewRankController;
use Modules\Crew\Http\Controllers\CrwCrewRequisitionController;
use Modules\Crew\Http\Controllers\CrwIncidentController;
use Modules\Crew\Http\Controllers\CrwPayrollBatchController;
use Modules\Crew\Http\Controllers\CrwPolicyController;
use Modules\Crew\Http\Controllers\CrwRankController;
use Modules\Crew\Http\Controllers\CrwRecruitmentApprovalController;
use Modules\Crew\Http\Controllers\CrwSalaryStructureController;
use Modules\Crew\Http\Controllers\CrwVesselRequiredCrewController;

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

// Route::middleware('auth:api')->get('/crew', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:api'])->prefix('crw')->as('crw.')->group(function ()
{
    Route::apiResource('crw-ranks', CrwRankController::class);
    Route::apiResource('crw-policies', CrwPolicyController::class);
    Route::apiResource('crw-crew-checklists', CrwCrewChecklistController::class);
    Route::apiResource('crw-vessel-required-crews', CrwVesselRequiredCrewController::class);
    Route::apiResource('crw-requisitions', CrwCrewRequisitionController::class);
    Route::apiResource('crw-recruitment-approvals', CrwRecruitmentApprovalController::class);
    Route::apiResource('crw-agencies', CrwAgencyController::class);
    Route::apiResource('crw-agency-contracts', CrwAgencyContractController::class);
    Route::apiResource('crw-agency-bills', CrwAgencyBillController::class);
    Route::apiResource('crw-crew-profiles', CrwCrewProfileController::class);
    Route::apiResource('crw-crew-documents', CrwCrewDocumentController::class);
    Route::apiResource('crw-crew-document-renewals', CrwCrewDocumentRenewalController::class);
    Route::apiResource('crw-crew-assignments', CrwCrewAssignmentController::class);
    Route::apiResource('crw-attendances', CrwAttendanceController::class);
    Route::apiResource('crw-incidents', CrwIncidentController::class);
    Route::apiResource('crw-salary-structures', CrwSalaryStructureController::class);
    Route::apiResource('crw-bank-accounts', CrwBankAccountController::class);
    Route::apiResource('crw-payroll-batches', CrwPayrollBatchController::class);
    Route::apiResource('appraisal-forms', AppraisalFormController::class);
    Route::apiResource('appraisal-records', AppraisalRecordController::class);

    //Additional Routes
    Route::get('crw-crew-document-renew-schedules', [CrwCrewDocumentController::class, 'renewScehdules']);


    //helper apis
    Route::post('get-crew-ranks', [CrwCommonController::class, 'getCrewRanks']);
    Route::post('get-crew-agencies', [CrwCommonController::class, 'getCrewAgencies']);
    Route::post('get-crew-agency-contracts', [CrwCommonController::class, 'getCrewAgencyContracts']);
    Route::post('get-crew-recruitment-approvals', [CrwCommonController::class, 'getCrewRecruitmentApprovals']);
    Route::post('get-crews', [CrwCommonController::class, 'getCrews']);
    Route::post('get-crew-documents', [CrwCommonController::class, 'getCrewDocuments']);
    Route::post('get-crew-document-renewals', [CrwCommonController::class, 'getCrewDocumentRenewals']);
    Route::post('get-vessel-assigned-crews', [CrwCommonController::class, 'getVesselAssignedCrews']);    
    Route::post('get-vessel-monthly-attendances', [CrwCommonController::class, 'getVesselMonthlyAttendances']);
    Route::post('get-crw-monthly-attendances', [CrwCommonController::class, 'getCrewMonthlyAttendances']);
    Route::post('get-appraisal-undone-assignments', [CrwCommonController::class, 'getAppraisalUndoneAssignments']);
    Route::post('get-appraisal-forms', [CrwCommonController::class, 'getAppraisalForms']);

    Route::post('update-crew-assign-status/{crw_crew_assignment}', [CrwCrewAssignmentController::class, 'updateCrewAssignStatus']);

});
