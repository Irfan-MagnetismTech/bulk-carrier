<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccAccountOpeningBalance;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

class AccAccountOpeningBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $accAccountOpeningBalances = AccAccountOpeningBalance::with('account')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->paginate(10);

            return response()->success('Retrieved Succesfully', $accAccountOpeningBalances, 200);
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
            $accountsOpeningBalanceData = $request->only('acc_cost_center_id', 'acc_account_id', 'date', 'dr_amount', 'cr_amount', 'business_unit');
            $accAccountOpeningBalance = AccAccountOpeningBalance::create($accountsOpeningBalanceData);

            return response()->success('Created Succesfully', $accAccountOpeningBalance, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccAccountOpeningBalance  $accAccountOpeningBalance
     */
    public function show(AccAccountOpeningBalance $accAccountOpeningBalance)
    {
        try {
            return response()->success('Retrieved succesfully', $accAccountOpeningBalance, 200);
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
     * @param  \App\Models\AccAccountOpeningBalance  $accAccountOpeningBalance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccAccountOpeningBalance $accAccountOpeningBalance) : JsonResponse
    {
        try {
            $accountsOpeningBalanceData = $request->only('acc_cost_center_id', 'acc_account_id', 'date', 'dr_amount', 'cr_amount', 'business_unit');
            $accAccountOpeningBalance->update($accountsOpeningBalanceData);

            return response()->success('Updated succesfully', $accAccountOpeningBalance, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccAccountOpeningBalance  $accAccountOpeningBalance
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccAccountOpeningBalance $accAccountOpeningBalance)
    {
        try {
            $accAccountOpeningBalance->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }        
    }
}
