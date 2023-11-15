<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccAccount;

class AccAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $accounts = AccAccount::with('balanceIncome', 'parent:id,account_name')
            ->globalSearch($request->all());

            return response()->success('Retrieved Successfully', $accounts, 200);
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
            $accAccountData = $request->only('acc_balance_and_income_line_id','parent_account_id','account_name','account_code','account_type','accountable_type','accountable_id','official_code','is_archived','business_unit');
            $accAccount     = AccAccount::create($accAccountData);

            return response()->success('Created Successfully', $accAccount, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccAccount  $accAccount
     * @return \Illuminate\Http\Response
     */
    public function show(AccAccount $accAccount)
    {
        try {
            return response()->success('Retrieved Successfully', $accAccount->load('balanceIncome', 'parent:id,account_name'), 200);
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
     * @param  \App\Models\AccAccount  $accAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccAccount $accAccount)
    {
        try {
            $accAccountData = $request->only('acc_balance_and_income_line_id','parent_account_id','account_name','account_code','account_type','accountable_type','accountable_id','official_code','is_archived','business_unit');
            $accAccount->update($accAccountData);

            return response()->success('Updated Successfully', $accAccountData, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccAccount  $accAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccAccount $accAccount)
    {
        try {
            $accAccount->delete();

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
