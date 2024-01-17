<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crew\Entities\CrwRestHourEntry;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Http\Requests\CrwRestHourEntryRequest;

class CrwRestHourEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwRestHourEntries = CrwRestHourEntry::with('opsVessel:id,name', 'crwRestHourEntryLines')->globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $crwRestHourEntries, 200);
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
                $crwRestHourEntryData = $request->only('ops_vessel_id', 'record_date', 'business_unit');

                $crwRestHourEntryLines = $request->crwRestHourEntryLines->map(function($q){
                    
                }); 

                $crwRestHourEntry     = CrwRestHourEntry::create($crwRestHourEntryData);
                $crwRestHourEntry->crwRestHourEntryLines()->createMany($request->crwRestHourEntryLines);
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
     * @param  \App\Models\CrwRestHourEntry  $crwRestHourEntry
     * @return \Illuminate\Http\Response
     */
    public function show(CrwRestHourEntry $crwRestHourEntry)
    {
        try {
            return response()->success('Retrieved succesfully', 
            $crwRestHourEntry->load('opsVessel:id,name', 'crwRestHourEntryLines.crwCrew:id,full_name,pre_mobile_no', 'crwRestHourEntryLines.crwCrewAssignment'), 200);
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
     * @param  \App\Models\CrwRestHourEntry  $crwRestHourEntry
     * @return \Illuminate\Http\Response
     */
    public function update(CrwRestHourEntryRequest $request, CrwRestHourEntry $crwRestHourEntry)
    {
        try {
            DB::transaction(function () use ($request, $crwRestHourEntry)
            {
                $crwRestHourEntryData = $request->only('ops_vessel_id', 'record_date', 'business_unit');
                $crwRestHourEntry->update($crwRestHourEntryData);
                $crwRestHourEntry->crwRestHourEntryLines()->delete();
                $crwRestHourEntry->crwRestHourEntryLines()->createMany($request->crwRestHourEntryLines);
            });
            return response()->success('Updated succesfully', $crwRestHourEntry, 202);

        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwRestHourEntry  $crwRestHourEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwRestHourEntry $crwRestHourEntry)
    {
        try {
            $crwRestHourEntry->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}