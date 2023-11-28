<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Accounts\Entities\AccAccount;
use Modules\Accounts\Entities\AccBalanceAndIncomeLine;
use Modules\Accounts\Entities\AccBankAccount;
use Modules\Accounts\Entities\AccCostCenter;
use Modules\Accounts\Entities\AccFixedAsset;
use Modules\Accounts\Entities\AccLoan;

class AccCommonController extends Controller
{
    public function getCostCenters(Request $request)
    {
        try {
            $costCenters = AccCostCenter::when(request()->business_unit != "ALL", function ($q) {
                $q->where('business_unit', request()->business_unit);
            })
            // ->when(request()->name, function($q) {
            //     $q->where('name', 'like', '%' . request()->name . '%');
            // })
            ->get();

            return response()->json([
                'status' => 'success',
                'value'  => $costCenters,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

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
            $items = AccAccount::with('balanceIncome:id,line_text')
            ->when(request()->business_unit != "ALL", function ($q) {
                $q->where('business_unit', request()->business_unit);
            })
            // ->when(request()->account_name, function($q) {
            //     $q->where('account_name', 'like', '%' . request()->account_name . '%');
            // })
            // ->limit(10)
            ->get(['id', 'account_name', 'acc_balance_and_income_line_id']);

            $response = [];
            foreach ($items as $item)
            {
                $response[] = [
                    'acc_account_name'             => $item->account_name,
                    'account_name'                 => $item->account_name,
                    'acc_account_id'               => $item->id,
                    'id'                           => $item->id,
                    'acc_balance_income_line_name' => $item->balanceIncome->line_text,
                    'acc_balance_income_line_id'   => $item->balanceIncome->id,
                    'acc_balance_and_income_line_id'   => $item->balanceIncome->id,
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


    public function generateAccountCode(Request $request)
    {

        try {
            $currentAccountCode = null;
            $balanceid          = AccBalanceAndIncomeLine::where('id', request()->balance_and_income_line_id)->firstOrFail()->ancestors->pluck('id')->implode('-');
            $lastAccount        = AccAccount::where('account_code', 'LIKE', "$balanceid-".request()->balance_and_income_line_id."-%")->latest()->first();

            if (!empty($lastAccount))
            {
                list(, , , $lastAccountSeq) = explode('-', $lastAccount->account_code);
                $currentAccountCode         = $lastAccountSeq + 1;
            }
            else
            {
                $currentAccountCode = 1;
            }

            return response()->json([
                'status' => 'success',
                'value'  => "$balanceid-".request()->balance_and_income_line_id."-$currentAccountCode",
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getLoans(Request $request)
    {
        try {
            $loans = AccLoan::when(request()->business_unit != "ALL", function ($q) {
                $q->where('business_unit', request()->business_unit);
            })
            ->get();

            return response()->json([
                'status' => 'success',
                'value'  => $loans,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getBanks(Request $request)
    {
        try {
            $loans = AccBankAccount::when(request()->business_unit != "ALL", function ($q) {
                $q->where('business_unit', request()->business_unit);
            })
            ->get();

            return response()->json([
                'status' => 'success',
                'value'  => $loans,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getFixedAssetCategories(Request $request)
    {

        try {
            $fixedAssetLine = config('accounts.balance_income_line.fixed_assets_at_cost'); 

            $loans = AccAccount::when(request()->business_unit != "ALL", function ($q) {
                $q->where('business_unit', request()->business_unit);
            })
            ->where('acc_balance_and_income_line_id', $fixedAssetLine)
            ->whereDoesntHave('parent')
            ->get();

            return response()->json([
                'status' => 'success',
                'value'  => $loans,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getFixedAssets(Request $request)
    {
        try {
            $fixedAssets = AccFixedAsset::when(request()->business_unit != "ALL", function ($q) {
                $q->where('business_unit', request()->business_unit);
            })
            ->when(request()->acc_cost_center_id, function($q){
                $q->where('acc_cost_center_id', 4);
            })
            ->get();

            return response()->json([
                'status' => 'success',
                'value'  => $fixedAssets,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    


}
