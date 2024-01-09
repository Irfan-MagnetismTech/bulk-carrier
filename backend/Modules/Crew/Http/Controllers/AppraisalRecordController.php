<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\AppraisalRecord;
use Modules\Crew\Entities\AppraisalRecordLine;
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
            $appraisalRecords = AppraisalRecord::with('crwCrew:id,full_name', 'crwCrewAssignment.opsVessel:id,name')
                ->globalSearch($request->all());

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
    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request)
            {
                $appraisalRecordData = $request->only('crw_crew_id', 'appraisal_form_id', 'crw_crew_assignment_id', 'appraisal_date', 'age', 'business_unit');

                $appraisalRecord = AppraisalRecord::create($appraisalRecordData);

                foreach ($request->appraisalRecordLines as $recordLineData)
                {
                    $recordLineData['appraisal_record_id'] = $appraisalRecord->id;
                    $appraisalRecordLine                   = AppraisalRecordLine::create($recordLineData);
                    $recordItems                           = $recordLineData['appraisalFormLineItems'];

                    if ($recordItems)
                    {
                        $appraisalRecordLine->appraisalRecordLineItems()->createMany($recordItems);
                    }
                }
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

            $appraisalRecord->load('crwCrew', 'appraisalForm', 'crwCrewAssignment.opsVessel', 'appraisalRecordLines.appraisalFormLine',
                'appraisalRecordLines.appraisalRecordLineItems.appraisalFormLineItem');
            

            $appraisalRecordLines = $appraisalRecord->appraisalRecordLines->map(function ($recordLine, $key)
            {
                $formattedSection = [
                    'section_no'               => $recordLine->appraisalFormLine->section_no,
                    'section_name'             => $recordLine->appraisalFormLine->section_name,
                    'is_tabular'               => $recordLine->appraisalFormLine->is_tabular,
                    'line_composite'           => $recordLine->line_composite,
                    'comment'                  => $recordLine->comment,
                    'answer'                   => $recordLine->answer,
                    'appraisalRecordLineItems' => $recordLine->appraisalRecordLineItems->map(function ($lineItem, $key) use ($recordLine)
                    {
                        $appraisalFormLineItem = [
                            'item_no'                  => $lineItem->appraisalFormLineItem->item_no,
                            'aspect'                   => $lineItem->appraisalFormLineItem->aspect,
                            'description'              => $lineItem->appraisalFormLineItem->description,
                            'answer_type'              => $lineItem->appraisalFormLineItem->answer_type,
                            'item_composite'           => $lineItem->item_composite,
                            'appraisal_record_line_id' => $lineItem->appraisal_record_line_id,
                            'comment'                  => $lineItem->comment,
                            'answer'                   => $lineItem->answer,
                        ];

                        return $appraisalFormLineItem;
                    }),
                ];

                return $formattedSection;
            });

            unset($appraisalRecord->appraisalRecordLines); 

            $appraisalRecord->appraisalRecordLines = $appraisalRecordLines; 



            return response()->success('Retrieved Successfully', $appraisalRecord, 200);
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
