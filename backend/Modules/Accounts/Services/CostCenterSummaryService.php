<?php

namespace Modules\Accounts\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Request;
use Modules\Accounts\Entities\AccAccount;
use Modules\Accounts\Entities\AccBalanceAndIncomeLine;
use Modules\Accounts\Entities\AccCostCenter;
use Modules\Accounts\Entities\AccLedgerEntry;

//create class
class CostCenterSummaryService extends Controller
{

    /**
     * @param $request
     * @return mixed
     */
    public function handleCostCenterSummaryService($request)
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
