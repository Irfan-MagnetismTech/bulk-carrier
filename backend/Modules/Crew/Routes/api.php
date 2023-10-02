<?php

use Illuminate\Support\Facades\Route;

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
    Route::apiResource('crw-requisitions', CrwRequisitionController::class);
    Route::apiResource('crw-recruitment-approvals', CrwRecruitmentApprovalController::class);
    Route::apiResource('crw-agencies', CrwAgencyController::class);
    Route::apiResource('crw-agency-contracts', CrwAgencyContractController::class);
    Route::apiResource('crw-agency-bills', CrwAgencyBillController::class);
    Route::apiResource('crw-crew-profiles', CrwCrewProfileController::class);
    Route::apiResource('crw-crews', CrwCrewController::class);
    Route::apiResource('crw-crew-ranks', CrwCrewRankController::class);
    Route::apiResource('crw-crew-documents', CrwCrewDocumentController::class);
    Route::apiResource('crw-crew-assignments', CrwCrewAssignmentController::class);
    Route::apiResource('crw-attendances', CrwAttendanceController::class);
    Route::apiResource('crw-incidents', CrwIncidentController::class);
    Route::apiResource('crw-salary-structures', CrwSalaryStructureController::class);
    Route::apiResource('crw-bank-accounts', CrwBankAccountController::class);
});
