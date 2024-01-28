<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\AppraisalForm;
use Modules\Crew\Entities\AppraisalFormLine;
use Modules\Crew\Entities\AppraisalFormLineItem;
use Modules\Crew\Http\Requests\AppraisalFormRequest;

class AppraisalFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $appraisalForms = AppraisalForm::with('appraisalFormLines')->globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $appraisalForms, 200);
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
                $appraisalFormData = $request->only('form_no', 'form_name', 'total_marks', 'version', 'description', 'business_unit');
                $appraisalForm     = AppraisalForm::create($appraisalFormData);

                foreach($request->appraisalFormLines as $lineData){
                    $lineData['line_composite'] = "F$appraisalForm->id-S".$lineData['section_no']; 
                    $appraisalFormLine = $appraisalForm->appraisalFormLines()->create($lineData);

                    foreach($lineData['appraisalFormLineItems'] as $itemData){
                        $itemData['item_composite'] = "$appraisalFormLine->line_composite-I".$itemData['item_no'];
                        $itemData['appraisal_form_line_id'] = $appraisalFormLine->id; 
                        AppraisalFormLineItem::create($itemData); 
                    }                    
                }
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
     * @param  \App\Models\AppraisalForm  $appraisalForm
     * @return \Illuminate\Http\Response
     */
    public function show(AppraisalForm $appraisalForm)
    {
        try {
            return response()->success('Retrieved succesfully', $appraisalForm->load('appraisalFormLines.appraisalFormLineItems'), 200);
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
     * @param  \App\Models\AppraisalForm  $appraisalForm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AppraisalForm $appraisalForm)
    {
        try {
            DB::transaction(function () use ($request, $appraisalForm)
            {
                $appraisalFormData = $request->only('form_no', 'form_name', 'total_marks', 'version', 'description', 'business_unit');
                $appraisalForm->update($appraisalFormData);
                $appraisalForm->appraisalFormLines()->delete();  
                

                foreach($request->appraisalFormLines as $lineData){
                    $lineData['line_composite'] = "F$appraisalForm->id-S".$lineData['section_no']; 
                    $appraisalFormLine = $appraisalForm->appraisalFormLines()->create($lineData);

                    foreach($lineData['appraisalFormLineItems'] as $itemData){
                        $itemData['item_composite'] = "$appraisalFormLine->line_composite-I".$itemData['item_no'];
                        $itemData['appraisal_form_line_id'] = $appraisalFormLine->id; 
                        AppraisalFormLineItem::create($itemData); 
                    }                    
                }
            });

            return response()->success('Updated Succesfully', [], 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AppraisalForm  $appraisalForm
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppraisalForm $appraisalForm)
    {
        try {
            $appraisalForm->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
