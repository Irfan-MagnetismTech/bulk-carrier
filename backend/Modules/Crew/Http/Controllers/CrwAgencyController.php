<?php

namespace Modules\Crew\Http\Controllers;

use App\Services\FileUploadService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crew\Entities\CrwAgency;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Http\Requests\CrwAgencyRequest;

class CrwAgencyController extends Controller
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
            $crwAgencies = CrwAgency::with('crwAgencyContactPersons')->globalSearch($request->all());

            return response()->success('Retrieved Successfully', $crwAgencies, 200);
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
    public function store(CrwAgencyRequest $request)
    {
        try {
            DB::transaction(function () use ($request)
            {
                $crwAgencyData = $request->only('agency_name', 'legal_name', 'tax_identification', 'business_license_no', 'company_reg_no', 'address', 'phone', 'email', 'website', 'logo', 'country', 'business_unit');
                $crwAgencyData['logo'] = $this->fileUpload->handleFile($request->logo, 'crw/agency');
                $crwAgency     = CrwAgency::create($crwAgencyData);
                $crwAgency->crwAgencyContactPersons()->createMany($request->crwAgencyContactPersons);

            });
            return response()->success('Created Successfully', null, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwAgency  $crwAgency
     * @return \Illuminate\Http\Response
     */
    public function show(CrwAgency $crwAgency)
    {
        try {
            return response()->success('Retrieved succesfully', $crwAgency->load('crwAgencyContactPersons'), 200);
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
     * @param  \App\Models\CrwAgency  $crwAgency
     * @return \Illuminate\Http\Response
     */
    public function update(CrwAgencyRequest $request, CrwAgency $crwAgency)
    {
        try {
            DB::transaction(function () use ($request, $crwAgency)
            {
                $crwAgencyData = $request->only('agency_name', 'legal_name', 'tax_identification', 'business_license_no', 'company_reg_no', 'address', 'phone', 'email', 'website', 'logo', 'country', 'business_unit');
                $crwAgencyData['logo'] = $this->fileUpload->handleFile($request->logo, 'crw/agency',$crwAgency->logo);
                $crwAgency->update($crwAgencyData);
                $crwAgency->crwAgencyContactPersons()->delete();
                $crwAgency->crwAgencyContactPersons()->createMany($request->crwAgencyContactPersons);

            });
            return response()->success('Updated successfully', $crwAgency, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwAgency  $crwAgency
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwAgency $crwAgency)
    {
        try {
            $crwAgency->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
