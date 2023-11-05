<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccTransaction;
use Illuminate\Database\QueryException;

class AccTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $crwCrewRanks = AccTransaction::with('ledgerEntries.account')->withCount('ledgerEntries as total_ledger')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })->paginate(10);

            return response()->success('Retrieved Succesfully', $crwCrewRanks, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $accTransactionData = $request->only('acc_cost_center_id', 'voucher_type', 'transactionable_id', 'transactionable_type', 'transaction_date', 'bill_no', 'mr_no', 'narration', 'instrument_type', 'instrument_no', 'instrument_date', 'user_id', 'business_unit');
            $accTransaction     = AccTransaction::create($accTransactionData);
            $accTransaction->ledgerEntries()->createMany($request->ledgerEntries);

            return response()->success('Created Successfully', $accTransaction, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccTransaction  $accTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(AccTransaction $accTransaction)
    {
        try {
            return response()->success('Retrieved successfully', $accTransaction->load('ledgerEntries.account','costCenter'), 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccTransaction  $accTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccTransaction $accTransaction)
    {
        try {
            $accTransactionData = $request->only('acc_cost_center_id', 'voucher_type', 'transactionable_id', 'transactionable_type', 'transaction_date', 'bill_no', 'mr_no', 'narration', 'instrument_type', 'instrument_no', 'instrument_date', 'user_id', 'business_unit');
            $accTransaction->update($accTransactionData);
            $accTransaction->ledgerEntries()->delete();
            $accTransaction->ledgerEntries()->createMany($request->ledgerEntries);

            return response()->success('Updated successfully', $accTransaction, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccTransaction  $accTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccTransaction $accTransaction)
    {
        try {
            $accTransaction->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}