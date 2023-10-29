<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crew\Entities\CrwRank;

class CrwRankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $crwRanks = CrwRank::when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })->paginate(10);

            return response()->success('Retrieved Succesfully', $crwRanks, 200);
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
            $crwRankData = $request->only('name', 'short_name', 'business_unit');
            $crwRank     = CrwRank::create($crwRankData);

            return response()->success('Created Succesfully', $crwRank, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwRank  $crwRank
     * @return \Illuminate\Http\Response
     */
    public function show(CrwRank $crwRank)
    {
        try {
            return response()->success('Retrieved succesfully', $crwRank, 200);
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
     * @param  \App\Models\CrwRank  $crwRank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CrwRank $crwRank)
    {
        try {
            $crwRankData = $request->only('name', 'short_name', 'business_unit');
            $crwRank->update($crwRankData);

            return response()->success('Updated succesfully', $crwRank, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwRank  $crwRank
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwRank $crwRank)
    {
        try {
            $crwRank->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
