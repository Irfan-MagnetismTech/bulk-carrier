<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccCashAccount;
use Illuminate\Database\QueryException;
use Modules\Accounts\Http\Requests\AccCashAccountRequest;

class AccCashAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $accCashAccounts = AccCashAccount::globalSearch($request->all());

            return response()->success('Retrieved Successfully', $accCashAccounts, 200);
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
    public function store(AccCashAccountRequest $request)
    {
        try {
            $accCashAccountData = $request->only('name', 'business_unit');
            $accCashAccount    = AccCashAccount::create($accCashAccountData);

            return response()->success('Created Successfully', $accCashAccount, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccCashAccount  $accCashAccount
     * @return \Illuminate\Http\Response
     */
    public function show(AccCashAccount $accCashAccount)
    {
        try {
            return response()->success('Retrieved Successfully', $accCashAccount, 200);
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
     * @param  \App\Models\AccCashAccount  $accCashAccount
     * @return \Illuminate\Http\Response
     */
    public function update(AccCashAccountRequest $request, AccCashAccount $accCashAccount)
    {
        try {
            $accCashAccountData = $request->only('name', 'business_unit');
            $accCashAccount->update($accCashAccountData);

            return response()->success('Updated Successfully', $accCashAccount, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccCashAccount  $accCashAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccCashAccount $accCashAccount)
    {
        try {
            $accCashAccount->delete();

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }        
    }
}
