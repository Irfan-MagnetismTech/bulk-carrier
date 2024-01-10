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
    public function handleAccountService($line_id, $name, $code, $Acc_type, $unit, $accontable_type, $accountable_id)
    {
        $data  =  [
            'acc_balance_and_income_line_id' => $line_id,
            'account_name'                   => $name,
            'account_code'                   => $code,
            'account_type'                   => $Acc_type,
            'business_unit'                  => $unit,
            'accountable_type'               => $accontable_type,
            'accountable_id'                 => $accountable_id,
        ];
        // $account = AccAccount::create($data);
        return $account;
    }

}
