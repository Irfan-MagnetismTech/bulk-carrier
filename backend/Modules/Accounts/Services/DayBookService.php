<?php

namespace Modules\Accounts\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Request;
use Modules\Accounts\Entities\AccLedgerEntry;

//create class
class DayBookService extends Controller
{

    /**
     * @param $request
     * @return mixed
     */
    public function handleDayBookService()
    {
        $ledgetEntries = AccLedgerEntry::with('transaction:id,transaction_date,voucher_type', 'account:id,account_name')
        ->when(request()->account_id, function ($q){
            $q->where('account_id', request()->account_id);
        })
        ->whereHas('transaction', function ($q){
            $q->whereBetween('transaction_date', [request()->from_date, request()->till_date]);
        })
        ->latest()
        ->get(['id', 'acc_cost_center_id', 'acc_transaction_id', 'acc_account_id', 'dr_amount', 'cr_amount']);

        return $ledgetEntries;
    }

}
