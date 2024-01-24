<?php

namespace Modules\Accounts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Accounts\Entities\AccAccount;
use Modules\Accounts\Entities\AccBalanceAndIncomeLine;
use Modules\Accounts\Entities\AccCostCenter;
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

            $balancesheets['income'] = (new TrialBalanceService)->calculateIncomeGrowthRate();

            $liabilitiesTotalAmount                   = $balancesheets['liabilities']->pluck('closing_balance_amount')->sum();
            $assetsTotalAmount                        = $balancesheets['assets']->pluck('closing_balance_amount')->sum();
            $balancesheets['grand_total_liabilities'] = round($liabilitiesTotalAmount + $balancesheets['income']['curr_year_opening'] + $balancesheets['income']['curr_year_income'], 2);
            $balancesheets['grand_total_assets']      = round($assetsTotalAmount, 2);

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

    /**
     * @param Request $request
     * @return mixed
     */
    public function fixedAssetStatement(Request $request)
    {
        $assets = AccFixedAsset::get()
            ->map(function ($asset)
        {
                $data = [
                    'particular'            => $asset->asset_tag,
                    'dep_rate'              => $asset->depreciation_rate,
                    'acquisition_date'      => $asset->acquisition_date,
                    'opening_cost'          => '',
                    'addition_cost'         => $add_cost = $asset->fixedAssetAccount?->ledgers->sum('dr_amount'),
                    'delation_cost'         => $del_cost = $asset->fixedAssetAccount?->ledgers->sum('cr_amount'),
                    'closing_cost'          => $cost = $add_cost - $del_cost,
                    'opening_depreciation'  => '',
                    'addition_depreciation' => $add_dep = $asset->acumulateDepreciationAccount?->ledgers->sum('dr_amount'),
                    'delation_depreciation' => $del_dep = $asset->acumulateDepreciationAccount?->ledgers->sum('cr_amount'),
                    'closing_depreciation'  => $dep = $add_dep - $del_dep,
                    'wdv'                   => $cost - $dep,
                ];

                return $data;
            });
        // dd('gg');

        return response()->json([
            'status' => 'success',
            'value'  => $assets,
        ], 200);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function costCenterSummary(Request $request)
    {
        $balanceIncomeLine = AccBalanceAndIncomeLine::whereId($request->acc_balance_income_line_id)->first();
        $account           = AccAccount::whereId($request->acc_account_id)->first();

        $costCenters = AccCostCenter::with(['ledgers' => function ($ledger)
        {
            $ledger->with('transaction:id,transaction_date')
                ->whereHas('transaction', fn($transaction) => $transaction->where('transaction_date', '<=', request()->till_date))
                ->when(request()->acc_account_id, fn($q) => $q->where('acc_account_id', request()->acc_account_id))
                ->when(request()->acc_balance_income_line_id, fn($q) => $q->where('acc_balance_and_income_line_id', request()->acc_balance_income_line_id))
                ->select(['id', 'acc_cost_center_id', 'acc_transaction_id', 'acc_account_id', 'acc_balance_and_income_line_id', 'dr_amount', 'cr_amount']);
        }])
        ->where('business_unit', $request->business_unit)
        ->get();

        $costCenterLines = $costCenters->map(function ($costCenter) use ($balanceIncomeLine)
        {
            [$prevLedgers, $currLedgers] = $costCenter->ledgers->partition(fn($q) => $q->transaction->transaction_date < request()->from_date);
            $prevDr                      = $prevLedgers->sum('dr_amount');
            $prevCr                      = $prevLedgers->sum('cr_amount');
            $currDr                      = $currLedgers->sum('dr_amount');
            $currCr                      = $currLedgers->sum('cr_amount');
            $openingBalance              = $balanceIncomeLine->value_type == 'D' ? $prevDr - $prevCr : $prevCr - $prevDr;
            $closingBalance              = $balanceIncomeLine->value_type == 'D' ? ($openingBalance + $currDr) - $currCr : ($openingBalance + $currCr) - $currDr;

            return
                [
                'cost_center_name' => $costCenter->name,
                'opening_balance' => $openingBalance,
                'current_dr'      => $currDr,
                'current_cr'      => $currCr,
                'closing_balance' => $closingBalance,
            ];
        });

        $costCentersInfo = [
            'balance_income_line_text' => $balanceIncomeLine->line_text,
            'account_name'             => $account?->account_name,
            'ttl_opening'              => $costCenterLines->sum('opening_balance'),
            'ttl_dr'                   => $costCenterLines->sum('current_dr'),
            'ttl_cr'                   => $costCenterLines->sum('current_cr'),
            'ttl_closing'              => $costCenterLines->sum('closing_balance'),
            'cost_center_lines'        => $costCenterLines,
        ];

        return $costCentersInfo;
    }

}
