<?php

namespace Modules\Accounts\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Accounts\Entities\AccAccount;
use Modules\Accounts\Entities\AccLedgerEntry;

class BulkUpdateController extends Controller
{
    public function addBalanceIncomeId()
    {
        $accountLedgers = AccLedgerEntry::get(['id', 'acc_account_id', 'acc_balance_and_income_line_id'])->groupBy('acc_account_id');

        $accountLedgers->map(function ($ledgers)
        {
            $account = AccAccount::where('id', $ledgers->first()->acc_account_id)->first();

            if (!empty($account->acc_balance_and_income_line_id))
            {
                $ledgers->map(function ($ledger) use ($account)
                {
                    $ledger->update(['acc_balance_and_income_line_id' => $account->acc_balance_and_income_line_id]);
                });
            }
        });

        return 'Operation Successfull.';
    }

    public function checkParentLessAccount()
    {
        return AccAccount::doesntHave('balanceIncome')->get();
    }

}
