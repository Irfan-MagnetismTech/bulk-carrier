<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwVesselRequiredCrew;
use Modules\Crew\Http\Requests\CrwVesselRequiredCrewRequest;

class CrwVesselRequiredCrewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwVesselRequiredCrews = CrwVesselRequiredCrew::with('crwVesselRequiredCrewLines','opsVessel:id,name,vessel_type,short_code')->globalSearch($request->all());

            return response()->success('Retrieved Successfully', $crwVesselRequiredCrews, 200);
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
    public function store(CrwVesselRequiredCrewRequest $request)
    {
        try {
            DB::transaction(function () use ($request)
            {
                $crwVesselRequiredCrewData = $request->only('ops_vessel_id', 'total_crew', 'effective_date', 'remarks', 'business_unit');
                $crwVesselRequiredCrew     = CrwVesselRequiredCrew::create($crwVesselRequiredCrewData);
                $crwVesselRequiredCrew->crwVesselRequiredCrewLines()->createMany($request->crwVesselRequiredCrewLines);

                return response()->success('Created Successfully', $crwVesselRequiredCrew, 201);
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
     * @param  \App\Models\CrwVesselRequiredCrew  $crwVesselRequiredCrew
     * @return \Illuminate\Http\Response
     */
    public function show(CrwVesselRequiredCrew $crwVesselRequiredCrew)
    {
        try {
            return response()->success('Retrieved Successfully', $crwVesselRequiredCrew->load('crwVesselRequiredCrewLines.crwRank','opsVessel'), 200);
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
     * @param  \App\Models\CrwVesselRequiredCrew  $crwVesselRequiredCrew
     * @return \Illuminate\Http\Response
     */
    public function update(CrwVesselRequiredCrewRequest $request, CrwVesselRequiredCrew $crwVesselRequiredCrew)
    {
        try {
            DB::transaction(function () use ($request, $crwVesselRequiredCrew)
            {
                $crwVesselRequiredCrewData = $request->only('ops_vessel_id', 'total_crew', 'effective_date', 'remarks', 'business_unit');
                $crwVesselRequiredCrew->update($crwVesselRequiredCrewData);
                $crwVesselRequiredCrew->crwVesselRequiredCrewLines()->delete();
                $crwVesselRequiredCrew->crwVesselRequiredCrewLines()->createMany($request->crwVesselRequiredCrewLines);

                return response()->success('Updated Successfully', $crwVesselRequiredCrew, 202);
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
     * @param  \App\Models\CrwVesselRequiredCrew  $crwVesselRequiredCrew
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwVesselRequiredCrew $crwVesselRequiredCrew)
    {
        try {
            $crwVesselRequiredCrew->delete();

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
