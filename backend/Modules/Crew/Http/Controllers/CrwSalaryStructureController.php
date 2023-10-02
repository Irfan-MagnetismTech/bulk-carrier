<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwSalaryStructure;

class CrwSalaryStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $crwSalaryStructures = CrwSalaryStructure::with('crwSalaryStructureBreakdowns')->get();

            return response()->success('Retrieved Succesfully', $crwSalaryStructures, 200);
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
                $crwSalaryStructureData = $request->only('crw_crew_id', 'increment_sequence', 'effective_date', 'promotion_id', 'currency', 'gross_salary', 'is_active', 'business_unit');
                $crwSalaryStructure     = CrwSalaryStructure::create($crwSalaryStructureData);
                $crwSalaryStructure->crwSalaryStructureBreakdowns()->createMany($request->crwSalaryStructureBreakdowns);

                return response()->success('Created Succesfully', $crwSalaryStructure, 201);
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
     * @param  \App\Models\CrwSalaryStructure  $crwSalaryStructure
     * @return \Illuminate\Http\Response
     */
    public function show(CrwSalaryStructure $crwSalaryStructure)
    {
        try {
            return response()->success('Retrieved succesfully', $crwSalaryStructure->load('crwSalaryStructureBreakdowns'), 200);
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
     * @param  \App\Models\CrwSalaryStructure  $crwSalaryStructure
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CrwSalaryStructure $crwSalaryStructure)
    {
        try {
            DB::transaction(function () use ($request, $crwSalaryStructure)
            {
                $crwSalaryStructureData = $request->only('crw_crew_id', 'increment_sequence', 'effective_date', 'promotion_id', 'currency', 'gross_salary', 'is_active', 'business_unit');
                $crwSalaryStructure->update($crwSalaryStructureData);
                $crwSalaryStructure->crwSalaryStructureBreakdowns()->delete();
                $crwSalaryStructure->crwSalaryStructureBreakdowns()->createMany($request->crwSalaryStructureBreakdowns);

                return response()->success('Updated succesfully', $crwSalaryStructure, 202);
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
     * @param  \App\Models\CrwSalaryStructure  $crwSalaryStructure
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwSalaryStructure $crwSalaryStructure)
    {
        try {
            $crwSalaryStructure->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
