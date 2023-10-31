<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccCashAccount;
use Illuminate\Database\QueryException;

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
            $accCashAccounts = AccCashAccount::when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->paginate(10);

            return response()->success('Retrieved Succesfully', $accCashAccounts, 200);
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
            $accCashAccountData = $request->only('name', 'business_unit');
            $accCashAccount    = AccCashAccount::create($accCashAccountData);

            return response()->success('Created Succesfully', $accCashAccount, 201);
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
            return response()->success('Retrieved succesfully', $accCashAccount, 200);
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
    public function update(Request $request, AccCashAccount $accCashAccount)
    {
        try {
            $accCashAccountData = $request->only('name', 'business_unit');
            $accCashAccount->update($accCashAccountData);

            return response()->success('Updated succesfully', $accCashAccount, 202);
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

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }        
    }
}
