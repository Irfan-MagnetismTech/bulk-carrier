<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crew\Entities\CrwBankAccount;
use App\Services\FileUploadService;

class CrwBankAccountController extends Controller
{
    public function __construct(private FileUploadService $fileUpload)
    {
    
    }    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwBankAccounts = CrwBankAccount::with('crwCrew')->globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $crwBankAccounts, 200);
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
            $crwBankAccountData = $request->only('crw_crew_id', 'bank_name', 'branch_name', 'routing_number', 'account_name', 'account_number', 'benificiary_name', 'is_active', 'business_unit');
            $crwBankAccountData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/crew-bank-account');

            $crwBankAccount     = CrwBankAccount::create($crwBankAccountData);

            return response()->success('Created Succesfully', $crwBankAccount, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwBankAccount  $crwBankAccount
     * @return \Illuminate\Http\Response
     */
    public function show(CrwBankAccount $crwBankAccount)
    {
        try {
            return response()->success('Retrieved Succesfully', $crwBankAccount->loan('crwCrew'), 200);
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
     * @param  \App\Models\CrwBankAccount  $crwBankAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CrwBankAccount $crwBankAccount)
    {
        try {
            $crwBankAccountData = $request->only('crw_crew_id', 'bank_name', 'branch_name', 'routing_number', 'account_name', 'account_number', 'benificiary_name', 'is_active', 'business_unit');
            $crwBankAccountData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/crew-bank-account', $crwBankAccount->attachment);

            $crwBankAccount->update($crwBankAccountData);

            return response()->success('Updated Succesfully', $crwBankAccount, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwBankAccount  $crwBankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwBankAccount $crwBankAccount)
    {
        try {
            $crwBankAccount->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
