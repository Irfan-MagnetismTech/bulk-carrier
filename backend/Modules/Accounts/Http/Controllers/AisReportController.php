<?php

namespace Modules\Accounts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Accounts\Entities\AccFixedAsset;
use Modules\Accounts\Services\DayBookService;
use Modules\Accounts\Services\LedgerService;
use Modules\Accounts\Services\TrialBalanceService;

class AisReportController extends Controller
{
    /**
     * @param Request $request
     */
    public function dayBook(Request $request): JsonResponse
    {
        try {
            $ledgetEntries = (new DayBookService)->handleDayBookService();

            return response()->json([
                'status' => 'success',
                'value'  => $ledgetEntries,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param Request $request
     */
    public function ledger(Request $request): JsonResponse
    {
        try {
            $ledgetEntries = (new LedgerService)->handleLedgerService();

            return response()->json([
                'status' => 'success',
                'value'  => $ledgetEntries,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param Request $request
     */
    public function trialBalance(Request $request): JsonResponse
    {
        try {
            $ledgetEntries = (new TrialBalanceService)->handleTrialBalanceService('trial_balance');

            return response()->json([
                'status' => 'success',
                'value'  => $ledgetEntries,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function incomeStatement()
    {
        try {
            $ledgetEntries = (new TrialBalanceService)->handleTrialBalanceService('income_statement')->groupBy('value_type')->mapWithKeys(function ($items, $key)
            {
                $key == 'C' ? $key = 'incomes' : $key = 'expense';

                return [$key => $items];
            });

            $incomeHeads  = $ledgetEntries['incomes'];
            $expenseHeads = $ledgetEntries['expense'];
            $totalIncome  = $incomeHeads->pluck('closing_balance_amount')->sum();
            $totalExpense = $expenseHeads->pluck('closing_balance_amount')->sum();

            $incomeOnSalesId    = config('accounts.balance_income_line.income_from_sales');
            $incomeOnServiceId  = config('accounts.balance_income_line.income_from_service');
            $expenseOnSalesId   = config('accounts.balance_income_line.expense_for_sales');
            $expenseOnServiceId = config('accounts.balance_income_line.expense_for_service');

            $incomeOnSales    = $incomeHeads->firstWhere('line_id', $incomeOnSalesId)['closing_balance_amount'];
            $incomeOnService  = $incomeHeads->firstWhere('line_id', $incomeOnServiceId)['closing_balance_amount'];
            $expenseOnSales   = $expenseHeads->firstWhere('line_id', $expenseOnSalesId)['closing_balance_amount'];
            $expenseOnService = $expenseHeads->firstWhere('line_id', $expenseOnServiceId)['closing_balance_amount'];

            $ledgetEntries['sale_performance']    = $this->setProfitLoss($incomeOnSales, $expenseOnSales, '_on_sale', $incomeOnSalesId, $expenseOnSalesId);
            $ledgetEntries['service_performance'] = $this->setProfitLoss($incomeOnService, $expenseOnService, '_on_service', $incomeOnServiceId, $expenseOnServiceId);
            $ledgetEntries['performance']         = $this->setProfitLoss($totalIncome, $totalExpense);
            $ledgetEntries['grand_total_income']  = $totalIncome + $ledgetEntries['performance']['loss'];
            $ledgetEntries['grand_total_expense'] = $totalExpense + $ledgetEntries['performance']['profit'];

            return response()->json([
                'status' => 'success',
                'value'  => $ledgetEntries,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function balanceSheet()
    {
        try {
            $balancesheets = (new TrialBalanceService)->handleTrialBalanceService('balancesheet')->groupBy('value_type')->mapWithKeys(function ($items, $key)
            {
                $key == 'C' ? $key = 'liabilities' : $key = 'assets';

                return [$key => $items];
            });

            $balancesheets['income']                  = (new TrialBalanceService)->calculateIncomeGrowthRate();

            $liabilitiesTotalAmount                   = $balancesheets['liabilities']->pluck('closing_balance_amount')->sum();
            $assetsTotalAmount                        = $balancesheets['assets']->pluck('closing_balance_amount')->sum();
            $balancesheets['grand_total_liabilities'] = round($liabilitiesTotalAmount + $balancesheets['income']['curr_year_opening'] + $balancesheets['income']['curr_year_income'], 2);
            $balancesheets['grand_total_assets']      = round( $assetsTotalAmount, 2 );

            return response()->json([
                'status' => 'success',
                'value'  => $balancesheets,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param $income
     * @param $expense
     * @param $basedOn
     */
    public function setProfitLoss($income, $expense, $basedOn = null, $saleId = null, $expenseId = null)
    {
        $profitLossInfo["profit$basedOn"] = 0;
        $profitLossInfo["loss$basedOn"]   = 0;
        $profitLossInfo['income_id']      = $saleId;
        $profitLossInfo['expense_id']     = $expenseId;
        $result                           = $income - $expense;

        if ($result > 0)
        {
            $profitLossInfo["profit$basedOn"] = $result;
        }
        else
        {
            $profitLossInfo["loss$basedOn"] = abs($result);
        }

        return $profitLossInfo;
    }

    public function fixedAssetStatement(){
        $assets = AccFixedAsset::get()
            ->map(function($asset){
                $data = [
                    'particular'           =>  $asset->asset_tag,
                    'dep_rate'             =>  $asset->depreciation_rate,
                    'acquisition_date'     =>  $asset->acquisition_date,
                    'opening_cost'         =>  '',
                    'addition_cost'        =>  $add_cost = $asset->fixedAssetAccount?->ledgers->sum('dr_amount'),
                    'delation_cost'        =>  $del_cost = $asset->fixedAssetAccount?->ledgers->sum('cr_amount'),
                    'closing_cost'         =>  $cost     = $add_cost - $del_cost,
                    'opening_depreciation' =>  '',
                    'addition_depreciation'=>  $add_dep = $asset->depreciationAccount?->ledgers->sum('dr_amount'),
                    'delation_depreciation'=>  $del_dep = $asset->depreciationAccount?->ledgers->sum('cr_amount'),
                    'closing_depreciation' =>  $dep     = $add_dep - $del_dep,
                    'wdv'                  =>  $cost - $dep
                ];

            return $data;
        });
        // dd('gg');
        return response()->json([
            'status' => 'success',
            'value'  => $assets,
        ], 200);
    }

}
