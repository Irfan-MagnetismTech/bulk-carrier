<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crew\Entities\CrwCrewChecklist;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Http\Requests\CrwCrewChecklistRequest;
use Modules\Crew\Http\Requests\CrwCrewRankRequest;

class CrwCrewChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwCrewChecklists = CrwCrewChecklist::with('crwCrewChecklistLines')->globalSearch($request->all());

            return response()->success('Retrieved Successfully', $crwCrewChecklists, 200);
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
    public function store(CrwCrewChecklistRequest $request)
    {
        try {
            DB::transaction(function () use ($request)
            {
                $crwCrewChecklistData = $request->only('effective_date', 'remarks', 'business_unit');
                $crwCrewChecklist     = CrwCrewChecklist::create($crwCrewChecklistData);
                $crwCrewChecklist->crwCrewChecklistLines()->createMany($request->crwCrewChecklistLines);

            });
            return response()->success('Created Successfully', '', 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwCrewChecklist  $crwCrewChecklist
     * @return \Illuminate\Http\Response
     */
    public function show(CrwCrewChecklist $crwCrewChecklist)
    {
        try {
            return response()->success('Retrieved Successfully', $crwCrewChecklist->load('crwCrewChecklistLines'), 200);
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
     * @param  \App\Models\CrwCrewChecklist  $crwCrewChecklist
     * @return \Illuminate\Http\Response
     */
    public function update(CrwCrewChecklistRequest $request, CrwCrewChecklist $crwCrewChecklist)
    {
        try {
            DB::transaction(function () use ($request, $crwCrewChecklist)
            {
                $crwCrewChecklistData = $request->only('effective_date', 'remarks', 'business_unit');
                $crwCrewChecklist->update($crwCrewChecklistData);
                $crwCrewChecklist->crwCrewChecklistLines()->delete();
                $crwCrewChecklist->crwCrewChecklistLines()->createMany($request->crwCrewChecklistLines);

            });
            return response()->success('Updated Successfully', '', 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwCrewChecklist  $crwCrewChecklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwCrewChecklist $crwCrewChecklist)
    {
        try {
            $crwCrewChecklist->delete();

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
