<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Maintenance\Entities\MntSurvey;
use Modules\Maintenance\Http\Requests\MntSurveyRequest;

class MntSurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {

            $surveys = MntSurvey::with(["mntSurveyItem", "mntSurveyType"])
                                ->globalSearch($request->all());

            return response()->success('Surveys are retrieved successfully', $surveys, 200);

        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function MntSurveys()
    {
        try {

            $surveys = MntSurvey::with(["mntSurveyItem", "mntSurveyType"])
                        ->where('mnt_survey_type_id', request()->mnt_survey_type_id)
                        ->where('mnt_survey_item_id', request()->mnt_survey_item_id)
                        ->get();

            return response()->success('Surveys are retrieved successfully', $surveys, 200);

        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('maintenance::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(MntSurveyRequest $request)
    {
        try {
            $input = $request->all();

            $survey = MntSurvey::create($input);

            return response()->success('Survey created successfully', $survey, 201);

        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        try {

            $survey = MntSurvey::with(["mntSurveyItem", "mntSurveyType"])->find($id);
            $survey->applySurveyNameModification = true;
            $survey->survey_name = $survey->survey_name;

            return response()->success('Survey type found successfully', $survey, 200);

        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('maintenance::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(MntSurveyRequest $request, $id)
    {
        try {
            $input = $request->all();

            $survey = MntSurvey::findorfail($id);
            $survey->update($input);

            return response()->success('Survey updated successfully', $survey, 202);

        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(MntSurvey $survey)
    {
        try {            
            DB::beginTransaction();
            $survey->delete();
            DB::commit();
            return response()->success('Survey deleted successfully', $survey, 204);
            
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->json($survey->preventDeletionIfRelated(), 422);

        }
    }
}
