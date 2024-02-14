<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccBankAccount;
use Modules\Accounts\Http\Requests\AccBankAccountRequest;
use Spatie\Permission\Traits\HasRoles;

class AccBankAccountController extends Controller
{
    use HasRoles;

    public function __construct()
    {
        $this->middleware('permission:acc-bank-account-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:acc-bank-account-create', ['only' => ['store']]);
        $this->middleware('permission:acc-bank-account-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:acc-bank-account-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        try {
            $accBankAccounts = AccBankAccount::globalSearch($request->all());

            return response()->success('Retrieved Successfully', $accBankAccounts, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     */
    public function store(AccBankAccountRequest $request)
    {
        try {
            $accBankAccountData = $request->only('bank_name', 'branch_name', 'account_type', 'account_name', 'account_number', 'routing_number', 'contact_number', 'opening_date', 'opening_balance', 'business_unit');
            $accBankAccount     = AccBankAccount::create($accBankAccountData);

            return response()->success('Created Successfully', $accBankAccount, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param AccBankAccount $accBankAccount
     */
    public function show(AccBankAccount $accBankAccount)
    {
        try {
            return response()->success('Retrieved Successfully', $accBankAccount, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }        
    }

    /**
     * @param Request $request
     * @param AccBankAccount $accBankAccount
     */
    public function update(AccBankAccountRequest $request, AccBankAccount $accBankAccount)
    {
        try {
            $accBankAccountData = $request->only('bank_name', 'branch_name', 'account_type', 'account_name', 'account_number', 'routing_number', 'contact_number', 'opening_date', 'opening_balance', 'business_unit');
            $accBankAccount->update($accBankAccountData);

            return response()->success('Updated Successfully', $accBankAccount, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param AccBankAccount $accBankAccount
     */
    public function destroy(AccBankAccount $accBankAccount)
    {
        try {
            $accBankAccount->delete();

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
