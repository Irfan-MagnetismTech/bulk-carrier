<?php

namespace Modules\Accounts\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Request;
use Modules\Accounts\Entities\AccBalanceAndIncomeLine;

//create class
class TrialBalanceService extends Controller
{
    /**
     * @param $request
     * @return mixed
     */

    private $reportType; 

    public function handleTrialBalanceService($reportType)
    {
        $this->reportType = $reportType; 
        
        // dd([request()->from_date, request()->till_date]);

        $balanceIncomeHeaders = AccBalanceAndIncomeLine::with('descendants.ledgers.transaction')
            ->with('descendants.parentAccounts.ledgers.transaction')
            ->with('descendants.parentAccounts.childAccounts.ledgers.transaction')
            ->with('descendants.parentAccounts.childAccounts.childAccounts.ledgers.transaction')
            ->when($this->reportType == 'trial_balance' || $this->reportType == 'all' , function ($q){
                $q->whereIn('line_type', ['income_header', 'balance_header']);
            })
            ->when($this->reportType == 'income_statement', function ($q){
                $q->where('line_type', 'income_header');
            })
            ->when($this->reportType == 'balancesheet', function ($q){
                $q->where('line_type', 'balance_header');
            })
            ->get(['id', 'line_type', 'line_text', 'value_type', 'acc_cost_center_id', 'parent_id', '_lft', '_rgt']);
        // ->first(['id', 'line_type', 'line_text', 'value_type', 'acc_cost_center_id', 'parent_id', '_lft', '_rgt']);

        $trialBalance = $balanceIncomeHeaders->map(function ($header)
        {
            $allLedgers      = $header->descendants->pluck('ledgers')->flatten();
            $previousLedgers = $allLedgers->where('transaction.transaction_date', '<', request()->from_date);
            $currentLedgers  = $allLedgers->whereBetween('transaction.transaction_date', [request()->from_date, request()->till_date]);

            $headerInfo      = $this->ledgerCalculator($previousLedgers, $currentLedgers, $header->value_type, $header->id, $header->line_text, '');

            $headerInfo['lines'] = $header->descendants->map(function ($balanceLine)
            {
                $allLedgers      = $balanceLine->ledgers->flatten();
                $previousLedgers = $allLedgers->where('transaction.transaction_date', '<', request()->from_date);
                $currentLedgers  = $allLedgers->whereBetween('transaction.transaction_date', [request()->from_date, request()->till_date]);
                $balanceLineInfo = $this->ledgerCalculator($previousLedgers, $currentLedgers, $balanceLine->value_type, $balanceLine->id, $balanceLine->line_text, '');

                $parentAccountInfo = $balanceLine->parentAccounts->map(function ($parentAccount) use ($balanceLine)
                {
                    $parentAccountLedgers = [];

                    if ($parentAccount->childAccounts->pluck('childAccounts')->collapse()->isNotEmpty())
                    {
                        $allLedgers           = $parentAccount->childAccounts->pluck('childAccounts')->flatten()->pluck('ledgers')->flatten();
                        $previousLedgers      = $allLedgers->where('transaction.transaction_date', '<', request()->from_date);
                        $currentLedgers       = $allLedgers->whereBetween('transaction.transaction_date', [request()->from_date, request()->till_date]);
                        $parentAccountLedgers = $this->ledgerCalculator($previousLedgers, $currentLedgers, $balanceLine->value_type, $parentAccount->id, $parentAccount->account_name, '');
                    }
                    else if ($parentAccount->childAccounts->isNotEmpty())
                    {
                        $allLedgers           = $parentAccount->childAccounts->pluck('ledgers')->flatten();
                        $previousLedgers      = $allLedgers->where('transaction.transaction_date', '<', request()->from_date);
                        $currentLedgers       = $allLedgers->whereBetween('transaction.transaction_date', [request()->from_date, request()->till_date]);
                        $parentAccountLedgers = $this->ledgerCalculator($previousLedgers, $currentLedgers, $balanceLine->value_type, $parentAccount->id, $parentAccount->account_name, '');
                    }
                    else
                    {
                        $allLedgers           = $parentAccount->ledgers;
                        $previousLedgers      = $allLedgers->where('transaction.transaction_date', '<', request()->from_date);
                        $currentLedgers       = $allLedgers->whereBetween('transaction.transaction_date', [request()->from_date, request()->till_date]);
                        $parentAccountLedgers = $this->ledgerCalculator($previousLedgers, $currentLedgers, $balanceLine->value_type, $parentAccount->id, $parentAccount->account_name, '');
                    }

                    //nested child accounts parentAccount->childAccount->grandChildAccount.
                    $childAccountLedgersInfo = $parentAccount->childAccounts->map(function ($childAccount) use ($balanceLine)
                    {
                        $childAccountLedgers = [];
                        $allLedgers          = $childAccount->ledgers;
                        $previousLedgers     = $allLedgers->where('transaction.transaction_date', '<', request()->from_date);
                        $currentLedgers      = $allLedgers->whereBetween('transaction.transaction_date', [request()->from_date, request()->till_date]);
                        $childAccountLedgers = $this->ledgerCalculator($previousLedgers, $currentLedgers, $balanceLine->value_type, $childAccount->id, $childAccount->account_name, '');

                        $grandChildAccountInfo = $childAccount->childAccounts->map(function ($grandChildAccount) use ($balanceLine)
                        {
                            $grandChildAccountLedgers = [];
                            $allLedgers               = $grandChildAccount->ledgers;
                            $previousLedgers          = $allLedgers->where('transaction.transaction_date', '<', request()->from_date);
                            $currentLedgers           = $allLedgers->whereBetween('transaction.transaction_date', [request()->from_date, request()->till_date]);
                            $grandChildAccountLedgers = $this->ledgerCalculator($previousLedgers, $currentLedgers, $balanceLine->value_type, $grandChildAccount->id, $grandChildAccount->account_name, '');

                            return $grandChildAccountLedgers;
                        });
                        $childAccountLedgers['grandchild_accounts'] = $grandChildAccountInfo;

                        return $childAccountLedgers;
                    });

                    $parentAccountLedgers['child_accounts'] = $childAccountLedgersInfo;

                    return $parentAccountLedgers;

                })->filter();

                $balanceLineInfo['parent_accounts'] = $parentAccountInfo;

                return $balanceLineInfo;
            });

            return $headerInfo;
        });

        return $trialBalance;
    }

    /**
     * @return mixed
     */
    public function calculateIncomeGrowthRate()
    {
        $balanceIncomeHeaders = AccBalanceAndIncomeLine::where('line_type', 'income_header')
            ->with('descendants.ledgers.transaction')->get(['id', 'line_type', 'line_text', 'value_type', 'acc_cost_center_id', 'parent_id', '_lft', '_rgt']);

        $profitSummary = $balanceIncomeHeaders->groupBy('value_type')->map(function ($item, $valueType)
        {
            $allLedgers      = $item->pluck('descendants')->flatten()->pluck('ledgers')->flatten();
            $previousLedgers = $allLedgers->where('transaction.transaction_date', '<', request()->from_date);
            $currentLedgers  = $allLedgers->whereBetween('transaction.transaction_date', [request()->from_date, request()->till_date]);

            $performance = [];
            if ($valueType == 'D')
            {
                $performance['prev_year_amount'] = abs($previousLedgers->sum('dr_amount') - $previousLedgers->sum('cr_amount'));
                $performance['curr_year_amount'] = abs($currentLedgers->sum('dr_amount') - $currentLedgers->sum('cr_amount'));
            }
            else
            {
                $performance['prev_year_amount'] = abs($previousLedgers->sum('cr_amount') - $previousLedgers->sum('dr_amount'));
                $performance['curr_year_amount'] = abs($currentLedgers->sum('cr_amount') - $currentLedgers->sum('dr_amount'));
            }

            return $performance;
        });

        $currYearOpening = $profitSummary['C']['prev_year_amount'] - $profitSummary['D']['prev_year_amount'];
        $currYearProfit  = $profitSummary['C']['curr_year_amount'] - $profitSummary['D']['curr_year_amount'];
        $currYearStatus  = $currYearProfit > 0 ? 'Profit' : 'Loss';

        return [
            'curr_year_opening' => round($currYearOpening, 2),
            'curr_year_status'  => $currYearStatus,
            'curr_year_income'  => round($currYearProfit, 2),
        ];
    }

    /**
     * @param $previousLedgers
     * @param $currentLedgers
     * @param $type
     * @return mixed
     */
    public function ledgerCalculator($previousLedgers, $currentLedgers, $valueType, $lineId, $lineName, $lineType)
    {
        $preDrAmount = $previousLedgers->sum('dr_amount');
        $preCrAmount = $previousLedgers->sum('cr_amount');
        $curDrAmount = $currentLedgers->sum('dr_amount');
        $curCrAmount = $currentLedgers->sum('cr_amount');

        $ledgerInfo                      = [];
        $ledgerInfo['line_id']           = $lineType . $lineId;
        $ledgerInfo['line_text']         = $lineName;
        $ledgerInfo['value_type']        = $valueType;
        $ledgerInfo['current_dr_amount'] = $curDrAmount;
        $ledgerInfo['current_cr_amount'] = $curCrAmount;

        if ($valueType == 'D')
        {
            $OpeningBalanceAmount                 = abs($preDrAmount - $preCrAmount);
            $ledgerInfo['opening_balance_status'] = 'Dr';
            $ledgerInfo['opening_balance_amount'] = abs($preDrAmount - $preCrAmount);

            if($this->reportType == 'income_statement'){
                $ledgerInfo['closing_balance_amount'] = abs( $curDrAmount - $curCrAmount );
            }else{
                $ledgerInfo['closing_balance_amount'] = abs($curDrAmount - $curCrAmount + $OpeningBalanceAmount);
            }
            $ledgerInfo['closing_balance_status'] = 'Dr';
        }
        else
        {
            $OpeningBalanceAmount = abs($preCrAmount - $preDrAmount);

            $ledgerInfo['opening_balance_status'] = 'Cr';
            $ledgerInfo['opening_balance_amount'] = abs($OpeningBalanceAmount);

            if($this->reportType == 'income_statement'){
                $ledgerInfo['closing_balance_amount'] = abs( $curCrAmount - $curDrAmount );
            }else{
                $ledgerInfo['closing_balance_amount'] = abs($curCrAmount - $curDrAmount + $OpeningBalanceAmount);
            }
            $ledgerInfo['closing_balance_status'] = 'Cr';
        }

        return $ledgerInfo;
    }

}
