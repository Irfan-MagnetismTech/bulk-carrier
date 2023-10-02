<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crew\Entities\CrwCrewChecklist;
use Illuminate\Support\Facades\DB;

class CrwCrewChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $crwCrewChecklists = CrwCrewChecklist::with('crwCrewChecklistLines')->get();

            return response()->success('Retrieved Succesfully', $crwCrewChecklists, 200);
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
                $crwCrewChecklistData = $request->only('effective_date', 'remarks', 'business_unit');
                $crwCrewChecklist     = CrwCrewChecklist::create($crwCrewChecklistData);
                $crwCrewChecklist->crwCrewChecklistLines()->createMany($request->crwCrewChecklistLines);

                return response()->success('Created Succesfully', $crwCrewChecklist, 201);
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
     * @param  \App\Models\CrwCrewChecklist  $crwCrewChecklist
     * @return \Illuminate\Http\Response
     */
    public function show(CrwCrewChecklist $crwCrewChecklist)
    {
        try {
            return response()->success('Retrieved succesfully', $crwCrewChecklist->load('crwCrewChecklistLines'), 200);
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
    public function update(Request $request, CrwCrewChecklist $crwCrewChecklist)
    {
        try {
            DB::transaction(function () use ($request, $crwCrewChecklist)
            {
                $crwCrewChecklistData = $request->only('effective_date', 'remarks', 'business_unit');
                $crwCrewChecklist->update($crwCrewChecklistData);
                $crwCrewChecklist->crwCrewChecklistLines()->delete();
                $crwCrewChecklist->crwCrewChecklistLines()->createMany($request->crwCrewChecklistLines);

                return response()->success('Updated succesfully', $crwCrewChecklist, 202);
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
     * @param  \App\Models\CrwCrewChecklist  $crwCrewChecklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwCrewChecklist $crwCrewChecklist)
    {
        try {
            $crwCrewChecklist->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
