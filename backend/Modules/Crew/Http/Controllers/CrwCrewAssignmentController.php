<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crew\Entities\CrwCrewAssignment;

class CrwCrewAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $crwCrewAssignments = CrwCrewAssignment::all();

            return response()->success('Retrieved Succesfully', $crwCrewAssignments, 200);
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
            $crwCrewAssignmentData = $request->only('ops_vessel_id', 'crw_crew_id', 'position_onboard', 'date_of_joining', 'port_of_joining', 'approx_duration', 'remarks', 'business_unit');
            $crwCrewAssignment     = CrwCrewAssignment::create($crwCrewAssignmentData);

            return response()->success('Created Succesfully', $crwCrewAssignment, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwCrewAssignment  $crwCrewAssignment
     * @return \Illuminate\Http\Response
     */
    public function show(CrwCrewAssignment $crwCrewAssignment)
    {
        try {
            return response()->success('Retrieved succesfully', $crwCrewAssignment, 200);
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
     * @param  \App\Models\CrwCrewAssignment  $crwCrewAssignment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CrwCrewAssignment $crwCrewAssignment)
    {
        try {
            $crwCrewAssignmentData = $request->only('ops_vessel_id', 'crw_crew_id', 'position_onboard', 'date_of_joining', 'port_of_joining', 'approx_duration', 'remarks', 'business_unit');
            $crwCrewAssignment->update($crwCrewAssignmentData);

            return response()->success('Updated succesfully', $crwCrewAssignment, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwCrewAssignment  $crwCrewAssignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwCrewAssignment $crwCrewAssignment)
    {
        try {
            $crwCrewAssignment->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
