<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Accounts\Entities\AccAccount;
use Modules\Accounts\Entities\AccBalanceAndIncomeLine;

class AccCommonController extends Controller
{
    public function getBalanceIncomeLinesOnly()
    {

        try {
            $balanceIncomeLines = AccBalanceAndIncomeLine::when(request()->business_unit != "ALL", function ($q) {
                $q->where('business_unit', request()->business_unit);
            })->whereIn('line_type', ['balance_line', 'income_line'])->orderBy('line_text')->get(['line_text', 'id']);

            return response()->json([
                'status' => 'success',
                'value'  => $balanceIncomeLines,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getBalanceIncomeAccounts(Request $request)
    {
        try {
            $balanceIncomeAccounts = AccAccount::when(request()->business_unit != "ALL", function ($q) {
                $q->where('business_unit', request()->business_unit);
            })->where('acc_balance_and_income_line_id', $request->acc_balance_and_income_line_id)->orderBy('account_name')->get(['account_name', 'id']);

            return response()->json([
                'status' => 'success',
                'value'  => $balanceIncomeAccounts,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getAccounts(Request $request)
    {
        try {

            $account_name = $request->account_name;
            if ($account_name == '')
            {
                $items = Account::limit(5)->get(['id', 'account_name', 'balance_and_income_line_id']);
            }
            else
            {
                $items = Account::with('balanceIncome:id,line_text')->where('account_name', 'like', '%' . $account_name . '%')
                    ->limit(10)->get(['id', 'account_name', 'balance_and_income_line_id']);
            }
            $response = [];
            foreach ($items as $item)
            {
                $response[] = [
                    'account_name'             => $item->account_name,
                    'account_id'               => $item->id,
                    'balance_income_line_name' => $item->balanceIncome->line_text,
                    'balance_income_line_id'   => $item->balanceIncome->id,
                ];
            }

            return response()->json([
                'status' => 'success',
                'value'  => $response,
            ], 200);

        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }


//
//    public function generateAccountCode($balanceIncomeLineId)
//    {
//
//        try {
//            $currentAccountCode = null;
//            $balanceid          = BalanceAndIncomeLine::where('id', $balanceIncomeLineId)->firstOrFail()->ancestors->pluck('id')->implode('-');
//            $lastAccount        = Account::where('account_code', 'LIKE', "$balanceid-$balanceIncomeLineId-%")->latest()->first();
//
//            if (!empty($lastAccount))
//            {
//                list(, , , $lastAccountSeq) = explode('-', $lastAccount->account_code);
//                $currentAccountCode         = $lastAccountSeq + 1;
//            }
//            else
//            {
//                $currentAccountCode = 1;
//            }
//
//            return response()->json([
//                'status' => 'success',
//                'value'  => "$balanceid-$balanceIncomeLineId-$currentAccountCode",
//            ], 200);
//        }
//        catch (\Exception $e)
//        {
//            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
//        }
//    }
//

}
