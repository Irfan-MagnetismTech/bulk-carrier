<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccAccountOpeningBalance;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Modules\Accounts\Http\Requests\AccAccountOpeningBalanceRequest;

class AccAccountOpeningBalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:acc-opening-balance-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:acc-opening-balance-create', ['only' => ['store']]);
        $this->middleware('permission:acc-opening-balance-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:acc-opening-balance-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $accAccountOpeningBalances = AccAccountOpeningBalance::with('account','costCenter')->globalSearch($request->all());

            return response()->success('Retrieved Successfully', $accAccountOpeningBalances, 200);
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
    public function store(AccAccountOpeningBalanceRequest $request)
    {
        try {
            $accountsOpeningBalanceData = $request->only('acc_cost_center_id', 'acc_account_id', 'date', 'dr_amount', 'cr_amount', 'business_unit');
            $accAccountOpeningBalance = AccAccountOpeningBalance::create($accountsOpeningBalanceData);

            return response()->success('Created Successfully', $accAccountOpeningBalance, 201);
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
            return response()->success('Retrieved Successfully', $accAccountOpeningBalance->load('account','costCenter'), 200);
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
    public function update(AccAccountOpeningBalanceRequest $request, AccAccountOpeningBalance $accAccountOpeningBalance) : JsonResponse
    {
        try {
            $accountsOpeningBalanceData = $request->only('acc_cost_center_id', 'acc_account_id', 'date', 'dr_amount', 'cr_amount', 'business_unit');
            $accAccountOpeningBalance->update($accountsOpeningBalanceData);

            return response()->success('Updated Successfully', $accAccountOpeningBalance, 202);
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

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
