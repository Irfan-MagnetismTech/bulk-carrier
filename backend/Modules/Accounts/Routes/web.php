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
use Modules\Accounts\Http\Controllers\BulkUpdateController;

Route::prefix('accounts')->group(function ()
{
    // Route::get('/', 'AccountsController@index');

    Route::get('add-balance-income-id', [BulkUpdateController::class, 'addBalanceIncomeId']);
    Route::get('check-parent-less-account', [BulkUpdateController::class, 'checkParentLessAccount']);
});
