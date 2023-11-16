<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crew\Entities\CrwCrew;

class CrwCrewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwCrews = CrwCrew::globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $crwCrews, 200);
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
            $crwCrewData = $request->only('crw_crew_profile_id', 'crw_rank_id', 'name', 'email', 'contact', 'business_unit');
            $crwCrew     = CrwCrew::create($crwCrewData);

            return response()->success('Created Succesfully', $crwCrew, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwCrew  $crwCrew
     * @return \Illuminate\Http\Response
     */
    public function show(CrwCrew $crwCrew)
    {
        try {
            return response()->success('Retrieved succesfully', $crwCrew, 200);
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
     * @param  \App\Models\CrwCrew  $crwCrew
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CrwCrew $crwCrew)
    {
        try {
            $crwCrewData = $request->only('crw_crew_profile_id', 'crw_rank_id', 'name', 'email', 'contact', 'business_unit');
            $crwCrew->update($crwCrewData);

            return response()->success('Updated succesfully', $crwCrew, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwCrew  $crwCrew
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwCrew $crwCrew)
    {
        try {
            $crwCrew->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
