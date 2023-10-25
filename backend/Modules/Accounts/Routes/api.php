<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Accounts\Http\Controllers\AccAccountController;
use Modules\Accounts\Http\Controllers\AccAccountOpeningBalanceController;
use Modules\Accounts\Http\Controllers\AccAdvanceAdjustmentController;
use Modules\Accounts\Http\Controllers\AccBalanceAndIncomeLineController;
use Modules\Accounts\Http\Controllers\AccBankAccountController;
use Modules\Accounts\Http\Controllers\AccBankReconciliationController;
use Modules\Accounts\Http\Controllers\AccCashAccountController;
use Modules\Accounts\Http\Controllers\AccCashRequisitionController;
use Modules\Accounts\Http\Controllers\AccCostCenterController;
use Modules\Accounts\Http\Controllers\AccDepreciationController;
use Modules\Accounts\Http\Controllers\AccFixedAssetController;
use Modules\Accounts\Http\Controllers\AccLoanController;
use Modules\Accounts\Http\Controllers\AccSalaryController;
use Modules\Accounts\Http\Controllers\AccSalaryHeadController;
use Modules\Accounts\Http\Controllers\AccTransactionController;
use Modules\Accounts\Http\Controllers\AccCommonController;

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

Route::middleware(['auth:api'])->prefix('acc')->as('acc.')->group(function ()
{
    Route::apiResource('acc-balance-and-income-lines', AccBalanceAndIncomeLineController::class);
    Route::apiResource('acc-cost-centers', AccCostCenterController::class);
    Route::apiResource('acc-accounts', AccAccountController::class);
    Route::apiResource('acc-account-opening-balances', AccAccountOpeningBalanceController::class);
    Route::apiResource('acc-bank-accounts', AccBankAccountController::class);
    Route::apiResource('acc-cash-accounts', AccCashAccountController::class);
    Route::apiResource('acc-transactions', AccTransactionController::class);
    Route::apiResource('acc-bank-reconciliations', AccBankReconciliationController::class);
    Route::apiResource('acc-loans', AccLoanController::class);
    Route::apiResource('acc-fixed-assets', AccFixedAssetController::class);
    Route::apiResource('acc-depreciations', AccDepreciationController::class);
    Route::apiResource('acc-cash-requisitions', AccCashRequisitionController::class);
    Route::apiResource('acc-advance-adjustments', AccAdvanceAdjustmentController::class);
    Route::apiResource('acc-salary-heads', AccSalaryHeadController::class);
    Route::apiResource('acc-salaries', AccSalaryController::class);


    //helper apis
    Route::post('get-balance-income-lines-only', [AccCommonController::class, 'getBalanceIncomeLinesOnly']);
    Route::post('get-balance-income-accounts', [AccCommonController::class, 'getBalanceIncomeAccounts']);


//    //Common routes
//    Route::get('get-balance-income-lines-only', [AccountsCommonController::class, 'getBalanceIncomeLinesOnly']);
//    Route::get('get-balance-income-accounts/{balance_and_income_line_id}', [AccountsCommonController::class, 'getBalanceIncomeAccounts']);
//    Route::get('generate-account-code/{balance_and_income_line_id}', [AccountsCommonController::class, 'generateAccountCode']);
//    Route::post('get-accounts', [AccountsCommonController::class, 'getAccounts']);
//
//    //AIS Reports
//    Route::post('day-book', [AisReportController::class, 'dayBook']);
//    Route::post('ledgers', [AisReportController::class, 'ledger']);
//    Route::post('trial-balance', [AisReportController::class, 'trialBalance']);
//    Route::post('income-statement', [AisReportController::class, 'incomeStatement']);
//    Route::post('balance-sheet', [AisReportController::class, 'balanceSheet']);
});
