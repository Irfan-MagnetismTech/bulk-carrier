<?php

namespace Modules\Crew\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwRank;
use Modules\Crew\Http\Requests\CrwRankRequest;

class CrwRankController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:crw-rank-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:crw-rank-create', ['only' => ['store']]);
        $this->middleware('permission:crw-rank-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:crw-rank-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwRanks = CrwRank::globalSearch($request->all());

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
    public function store(CrwRankRequest $request)
    {
        try {
            $crwRankData = $request->only('name', 'short_name', 'business_unit');
            $crwRank     = CrwRank::create($crwRankData);

            return response()->success('Created Successfully', $crwRank, 201);
        }
        catch (Exception $e)
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
            return response()->success('Retrieved Succesfully', $crwRank, 200);
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
    public function update(CrwRankRequest $request, CrwRank $crwRank)
    {
        try {
            $crwRankData = $request->only('name', 'short_name', 'business_unit');
            $crwRank->update($crwRankData);

            return response()->success('Updated Successfully', $crwRank, 202);
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
            DB::beginTransaction();

            $crwRank->delete();
            DB::commit();
            return response()->success('Deleted Successfully', null, 204);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->json($crwRank->preventDeletionIfRelated(), 422);            
        }
    }
}
