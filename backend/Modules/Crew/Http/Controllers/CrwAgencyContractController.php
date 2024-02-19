<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crew\Entities\CrwAgencyContract;
use Illuminate\Database\QueryException;
use App\Services\FileUploadService;
use Modules\Crew\Http\Requests\CrwAgencyContractRequest;

class CrwAgencyContractController extends Controller
{

    public function __construct(private FileUploadService $fileUpload)
    {
        $this->middleware('permission:crw-agency-contract-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:crw-agency-contract-create', ['only' => ['store']]);
        $this->middleware('permission:crw-agency-contract-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:crw-agency-contract-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwAgencyContracts = CrwAgencyContract::with('crwAgency:id,agency_name')->globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $crwAgencyContracts, 200);
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
    public function store(CrwAgencyContractRequest $request)
    {
        try {
            $crwAgencyContractData = $request->only('crw_agency_id', 'contract_name', 'billing_cycle', 'billing_currency', 'validity_from', 'validity_till', 'service_offered', 'terms_and_conditions', 'remarks', 'account_holder_name', 'bank_name', 'bank_address', 'account_no', 'swift_code', 'business_unit');
            $crwAgencyContractData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/agency-contract');

            $crwAgencyContract     = CrwAgencyContract::create($crwAgencyContractData);

            return response()->success('Created Successfully', $crwAgencyContract, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwAgencyContract  $crwAgencyContract
     * @return \Illuminate\Http\Response
     */
    public function show(CrwAgencyContract $crwAgencyContract)
    {
        try {
            return response()->success('Retrieved successfully', $crwAgencyContract->load('crwAgency'), 200);
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
     * @param  \App\Models\CrwAgencyContract  $crwAgencyContract
     * @return \Illuminate\Http\Response
     */
    public function update(CrwAgencyContractRequest $request, CrwAgencyContract $crwAgencyContract)
    {
        try {
            $crwAgencyContractData = $request->only('crw_agency_id', 'contract_name', 'billing_cycle', 'billing_currency', 'validity_from', 'validity_till', 'service_offered', 'terms_and_conditions', 'remarks', 'account_holder_name', 'bank_name', 'bank_address', 'account_no', 'swift_code', 'business_unit');
            $crwAgencyContractData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/agency-contract', $crwAgencyContract->attachment);

            $crwAgencyContract->update($crwAgencyContractData);

            return response()->success('Updated succesfully', $crwAgencyContract, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwAgencyContract  $crwAgencyContract
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwAgencyContract $crwAgencyContract)
    {
        try {
            $crwAgencyContract->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
