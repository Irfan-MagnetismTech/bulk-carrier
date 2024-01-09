<?php

namespace Modules\Accounts\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Request;
use Modules\Accounts\Entities\AccAccount;
use Modules\Accounts\Entities\AccLedgerEntry;

//create class
class AccountService extends Controller
{

    /**
     * @param $request
     * @return mixed
     */
    public function handleAccountService($accountData)
    {
        return [
            'acc_balance_and_income_line_id' => $accountData['balance_income_line_id'],
            'account_name' => $accountData['name'],
            'account_code' => $accountData['code'],
            // 'account_code' => $this->generateAccountCode(),
            'account_type' => $accountData['type'],
            'business_unit'=> $accountData['unit'],
            'accountable_type' => $accountData['accountable_type'],
            'accountable_id' => $accountData['id']
        ];
    }

    private function generateAccountCode(): string
    {
        return config('accounts.account_types.Assets') . ' - ' . config('accounts.balance_income_balance_header.current_assets') . ' - ' . config('accounts.balance_income_line.inventory') . ' - ' . 1;
    }

    // public function storeAccount(){

    // }

}
