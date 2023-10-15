<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crew\Entities\CrwAgency;
use Illuminate\Support\Facades\DB;


class CrwAgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $crwAgencies = CrwAgency::with('crwAgencyContactPeople')->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->paginate(10);

            return response()->success('Retrieved Succesfully', $crwAgencies, 200);
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
            DB::transaction(function () use ($request)
            {
                $crwAgencyData = $request->only('name', 'legal_name', 'tax_identification', 'business_license_no', 'company_reg_no', 'address', 'website', 'phone', 'email', 'logo', 'country', 'business_unit');
                $crwAgency     = CrwAgency::create($crwAgencyData);
                $crwAgency->crwAgencyContactPeople()->createMany($request->crwAgencyContactPeople);

                return response()->success('Created Succesfully', $crwAgency, 201);
            });
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
            return response()->success('Retrieved succesfully', $crwAgency->load('crwAgencyContactPeople'), 200);
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
    public function update(Request $request, CrwAgency $crwAgency)
    {
        try {
            DB::transaction(function () use ($request, $crwAgency)
            {
                $crwAgencyData = $request->only('name', 'legal_name', 'tax_identification', 'business_license_no', 'company_reg_no', 'address', 'website', 'phone', 'email', 'logo', 'country', 'business_unit');
                $crwAgency->update($crwAgencyData);
                $crwAgency->crwAgencyContactPeople()->delete();
                $crwAgency->crwAgencyContactPeople()->createMany($request->crwAgencyContactPeople);

                return response()->success('Updated succesfully', $crwAgency, 202);
            });
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
