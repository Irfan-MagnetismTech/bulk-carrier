<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwSalaryStructure;
use Modules\Crew\Http\Requests\CrwSalaryStructureRequest;

class CrwSalaryStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwSalaryStructures = CrwSalaryStructure::with('crwSalaryStructureBreakdowns','crwCrewProfile')->globalSearch($request->all());

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
    public function store(CrwSalaryStructureRequest $request)
    {
        try {
            $crwSalaryStructureData = $request->only('crw_crew_id', 'promotion_id', 'increment_sequence', 'effective_date', 'currency', 'gross_salary', 'addition', 'deduction', 'net_amount', 'is_active', 'business_unit', 'remarks');
            $crwSalaryStructure     = CrwSalaryStructure::create($crwSalaryStructureData);

            return response()->success('Created Successfully', [], 201);
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
            return response()->success('Retrieved successfully', $crwSalaryStructure->load('crwSalaryStructureBreakdowns','crwCrewProfile'), 200);
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
    public function update(CrwSalaryStructureRequest $request, CrwSalaryStructure $crwSalaryStructure)
    {
        try {
            $crwSalaryStructureData = $request->only('crw_crew_id', 'promotion_id', 'increment_sequence', 'effective_date', 'currency', 'gross_salary', 'addition', 'deduction', 'net_amount', 'is_active', 'business_unit', 'remarks');
            $crwSalaryStructure->update($crwSalaryStructureData);

            return response()->success('Updated successfully', $crwSalaryStructure, 202);
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
