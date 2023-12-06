<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crew\Entities\CrwAttendance;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Http\Requests\CrwAttendanceRequest;

class CrwAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwAttendances = CrwAttendance::with('opsVessel:id,name', 'crwAttendanceLines')->globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $crwAttendances, 200);
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
    public function store(CrwAttendanceRequest $request)
    {
        try {
            DB::transaction(function () use ($request)
            {
                $crwAttendanceData = $request->only('ops_vessel_id', 'year_month', 'working_days', 'total_crews', 'remarks', 'business_unit');
                $crwAttendance     = CrwAttendance::create($crwAttendanceData);
                $crwAttendance->crwAttendanceLines()->createMany($request->crwAttendanceLines);
            });
            return response()->success('Created Succesfully', [], 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwAttendance  $crwAttendance
     * @return \Illuminate\Http\Response
     */
    public function show(CrwAttendance $crwAttendance)
    {
        try {
            return response()->success('Retrieved succesfully', $crwAttendance->load('opsVessel:id,name', 'crwAttendanceLines.crwCrewAssignment.crwCrew'), 200);
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
     * @param  \App\Models\CrwAttendance  $crwAttendance
     * @return \Illuminate\Http\Response
     */
    public function update(CrwAttendanceRequest $request, CrwAttendance $crwAttendance)
    {
        try {
            DB::transaction(function () use ($request, $crwAttendance)
            {
                $crwAttendanceData = $request->only('ops_vessel_id', 'year_month', 'working_days', 'total_crews', 'remarks', 'business_unit');
                $crwAttendance->update($crwAttendanceData);
                $crwAttendance->crwAttendanceLines()->delete();
                $crwAttendance->crwAttendanceLines()->createMany($request->crwAttendanceLines);
            });
            return response()->success('Updated succesfully', $crwAttendance, 202);

        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwAttendance  $crwAttendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwAttendance $crwAttendance)
    {
        try {
            $crwAttendance->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
