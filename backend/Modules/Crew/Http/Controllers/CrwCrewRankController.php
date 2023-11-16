<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crew\Entities\CrwCrewRank;

class CrwCrewRankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwCrewRanks = CrwCrewRank::globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $crwCrewRanks, 200);
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
            $crwCrewRankData = $request->only('crw_crew_id', 'crw_rank_id', 'rank_name', 'effective_date', 'business_unit');
            $crwCrewRank     = CrwCrewRank::create($crwCrewRankData);

            return response()->success('Created Succesfully', $crwCrewRank, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwCrewRank  $crwCrewRank
     * @return \Illuminate\Http\Response
     */
    public function show(CrwCrewRank $crwCrewRank)
    {
        try {
            return response()->success('Retrieved succesfully', $crwCrewRank, 200);
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
     * @param  \App\Models\CrwCrewRank  $crwCrewRank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CrwCrewRank $crwCrewRank)
    {
        try {
            $crwCrewRankData = $request->only('crw_crew_id', 'crw_rank_id', 'rank_name', 'effective_date', 'business_unit');
            $crwCrewRank->update($crwCrewRankData);

            return response()->success('Updated succesfully', $crwCrewRank, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwCrewRank  $crwCrewRank
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwCrewRank $crwCrewRank)
    {
        try {
            $crwCrewRank->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
