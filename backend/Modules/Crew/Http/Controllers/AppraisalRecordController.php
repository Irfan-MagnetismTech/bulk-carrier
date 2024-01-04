<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\AppraisalRecord;
use Modules\Crew\Http\Requests\AppraisalRecordRequest;

class AppraisalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $appraisalRecords = AppraisalRecord::with('appraisalRecordLines.appraisalFormLineItem')->globalSearch($request->all());

            return response()->success('Retrieved Successfully', $appraisalRecords, 200);
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
    public function store(AppraisalRecordRequest $request)
    {
        try {
            DB::transaction(function () use ($request)
            {
                $appraisalRecordData = $request->only('crw_crew_id', 'appraisal_form_id', 'crw_crew_assignment_id', 'appraisal_date', 'age', 'business_unit');
                $appraisalRecord     = AppraisalRecord::create($appraisalRecordData);
                $appraisalRecord->appraisalRecordLines()->createMany($request->appraisalRecordLines);

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
     * @param  \App\Models\AppraisalRecord  $appraisalRecord
     * @return \Illuminate\Http\Response
     */
    public function show(AppraisalRecord $appraisalRecord)
    {
        try {
            return response()->success('Retrieved Successfully', $appraisalRecord->load('appraisalRecordLines'), 200);
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
     * @param  \App\Models\AppraisalRecord  $appraisalRecord
     * @return \Illuminate\Http\Response
     */
    public function update(AppraisalRecordRequest $request, AppraisalRecord $appraisalRecord)
    {        
        try {
            DB::transaction(function () use ($request, $appraisalRecord)
            {
                $appraisalRecordData = $request->only('crw_crew_id', 'appraisal_form_id', 'crw_crew_assignment_id', 'appraisal_date', 'age', 'business_unit');
                $appraisalRecord->update($appraisalRecordData);
                $appraisalRecord->appraisalRecordLines()->delete();
                $appraisalRecord->appraisalRecordLines()->createMany($request->appraisalRecordLines);
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
     * @param  \App\Models\AppraisalRecord  $appraisalRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppraisalRecord $appraisalRecord)
    {
        try {
            $appraisalRecord->delete();

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
