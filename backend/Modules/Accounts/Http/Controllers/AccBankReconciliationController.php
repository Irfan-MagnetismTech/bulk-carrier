<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccBankReconciliation;
use Modules\Accounts\Entities\AccTransaction;
use Illuminate\Database\QueryException;

class AccBankReconciliationController extends Controller
{
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
            ->whereIn('voucher_type', ['Receipt', 'Payment', 'Contra'])            
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->latest()
            ->paginate(10);

            return response()->success('Retrieved Succesfully', $accTransactions, 200);
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
    public function store(Request $request)
    {
        try {
            $bankReconciliationData            = $request->only('date', 'acc_transaction_id', 'business_unit');
            $bankReconciliationData['status']  = 'Complete';
            $accBankReconciliation             = AccBankReconciliation::create($bankReconciliationData);

            return response()->success('Created Succesfully', $accBankReconciliation, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccBankReconciliation  $accBankReconciliation
     * @return \Illuminate\Http\Response
     */
    public function show(AccBankReconciliation $accBankReconciliation)
    {
        // try {
        //     return response()->json([
        //         'status' => 'success',
        //         'value'  => $accBankReconciliation,
        //     ], 200);
        // }
        // catch (\Exception $e)
        // {
        //     return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        // }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccBankReconciliation  $accBankReconciliation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccBankReconciliation $accBankReconciliation)
    {
        // try {
        //     $bankReconciliationData = $request->only('acc_transaction_id', 'date', 'status', 'user_id');
        //     $accBankReconciliation->update($bankReconciliationData);

        //     return response()->json([
        //         'status' => 'success',
        //         'value'  => $accBankReconciliation,
        //     ], 200);
        // }
        // catch (\Exception $e)
        // {
        //     return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccBankReconciliation  $accBankReconciliation
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccBankReconciliation $accBankReconciliation)
    {
        // try {

        //     $accBankReconciliation->delete();

        //     return response()->json([
        //         'status' => 'success',
        //         'value'  => null,
        //     ], 200);
        // }
        // catch (\Exception $e)
        // {
        //     return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        // }
    }
}
