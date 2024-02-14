<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccBankReconciliation;
use Modules\Accounts\Entities\AccTransaction;
use Illuminate\Database\QueryException;
use Modules\Accounts\Http\Requests\AccBankReconciliationRequest;

class AccBankReconciliationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:acc-bank-reconciliation', ['only' => ['index', 'store']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $bankBalanceIncomeLineId = config('accounts.balance_income_line.bank');

            $accTransactions = AccTransaction::with('ledgerEntries.account', 'bankReconciliation')
            ->withCount('ledgerEntries as total_ledger')
            ->wherehas('ledgerEntries.account', function ($q) use ($bankBalanceIncomeLineId){
                $q->where('acc_balance_and_income_line_id', $bankBalanceIncomeLineId);
            })
            ->where('voucher_type', '!=', 'Journal')
            ->globalSearch($request->all());  

            return response()->success('Retrieved Successfully', $accTransactions, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccBankReconciliationRequest $request)
    {
        try {
            $bankReconciliationData            = $request->only('reconciliation_date', 'acc_transaction_id', 'business_unit');
            $bankReconciliationData['status']  = 'Complete';
            $accBankReconciliation             = AccBankReconciliation::create($bankReconciliationData);

            return response()->success('Created Successfully', $accBankReconciliation, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }        
    }
}
