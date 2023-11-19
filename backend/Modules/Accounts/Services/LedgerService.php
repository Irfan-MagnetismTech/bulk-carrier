<?php

namespace Modules\Accounts\Services;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Account;
use App\Models\Accounts\AccountOpeningBalance;
use App\Models\Accounts\LedgerEntry;
use Illuminate\Http\Client\Request;
use Modules\Accounts\Entities\AccAccount;
use Modules\Accounts\Entities\AccAccountOpeningBalance;
use Modules\Accounts\Entities\AccLedgerEntry;

//create class
class LedgerService extends Controller
{
    /**
     * @param $request
     * @return mixed
     */
    public function handleLedgerService()
    {
        $ledgerInfo = [];

        $account = AccAccount::with('accountOpeningBalance')->where('id', request()->acc_account_id)->first();

        $aob = AccAccountOpeningBalance::where('acc_account_id', request()->acc_account_id)->first();
        if(!empty($account->accountOpeningBalance)){
            $accountOpeningDrAmount = $aob->dr_amount;
            $accountOpeningCrAmount = $aob->cr_amount;
        }else{
            $accountOpeningDrAmount = 0;
            $accountOpeningCrAmount = 0;
        }

        $previousLedgers                     = $this->previousLedgers();
        $currentLedgers                      = $this->currentLedgers();

        $previousLedgersDrAmount             = $previousLedgers->sum('dr_amount');
        $previousLedgersCrAmount             = $previousLedgers->sum('cr_amount');
        $currentLedgersDrAmount              = $currentLedgers->sum('dr_amount');
        $currentLedgersCrAmount              = $currentLedgers->sum('cr_amount');

        $openingBalance                      = $this->openingBalance($account->account_type, $previousLedgersDrAmount, $previousLedgersCrAmount, $accountOpeningDrAmount, $accountOpeningCrAmount);
        $closingBalance                      = $this->openingBalance($account->account_type, $currentLedgersDrAmount, $currentLedgersCrAmount, $previousLedgersDrAmount, $previousLedgersCrAmount);

        $ledgerInfo['account_name']          = $account->account_name;
        $ledgerInfo['opening_dr_amount']     = $openingBalance['dr_amount'];
        $ledgerInfo['opening_cr_amount']     = $openingBalance['cr_amount'];
        $ledgerInfo['closing_dr_amount']     = $closingBalance['dr_amount'];
        $ledgerInfo['closing_cr_amount']     = $closingBalance['cr_amount'];
        $ledgerInfo['currentLedgers']        = $currentLedgers;

        return $ledgerInfo;
    }

    /**
     * @return mixed
     */
    public function previousLedgers()
    {
        $ledgers = AccLedgerEntry::where('acc_account_id', request()->acc_account_id)
            ->whereHas('transaction', function ($q){
                $q->whereDate('transaction_date', '<', request()->from_date);
            })->get(['id', 'acc_transaction_id', 'acc_account_id', 'dr_amount', 'cr_amount', 'acc_cost_center_id']);

        return $ledgers;
    }

    /**
     * @return mixed
     */
    public function currentLedgers()
    {
        $ledgers = AccLedgerEntry::with('transaction:id,transaction_date,voucher_type')->where('acc_account_id', request()->acc_account_id)
            ->whereHas('transaction', function ($q){
                $q->whereBetween('transaction_date', [request()->from_date, request()->till_date]);
            })
            ->get(['id', 'acc_transaction_id', 'acc_account_id', 'dr_amount', 'cr_amount', 'acc_cost_center_id']);

        return $ledgers;
    }

    public function openingBalance($account_type, $drAmount, $crAmount, $accountOpeningDrAmount = 0, $accountOpeningCrAmount = 0) {
        $config = config('accounts.account_types');
        $balanceInfo = [];

        if(in_array($account_type, [$config['Assets'], $config['Expenses']])){
            $openingBalance = $accountOpeningDrAmount + $drAmount - $crAmount;
            $balanceInfo['dr_amount']   = 0.00;
            $balanceInfo['cr_amount']   = 0.00;
            $openingBalance > 0 ? $balanceInfo['dr_amount'] = abs($openingBalance) : $balanceInfo['cr_amount'] = abs($openingBalance);
        }

        if(in_array($account_type, [$config['Liabilities'], $config['Revenues']])){
            $openingBalance = $accountOpeningCrAmount + $crAmount - $drAmount;
            $balanceInfo['dr_amount']   = 0.00;
            $balanceInfo['cr_amount']   = abs($openingBalance);
            $openingBalance > 0 ? $balanceInfo['cr_amount'] = abs($openingBalance) : $balanceInfo['dr_amount'] = abs($openingBalance);
        }

        return $balanceInfo;
    }


}
