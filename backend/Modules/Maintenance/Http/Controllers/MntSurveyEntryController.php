<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Maintenance\Entities\MntSurveyEntry;

class MntSurveyEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {

            $surveyEntries = MntSurveyEntry::with(["opsVessel","mntSurvey.mntSurveyItem","mntSurvey.mntSurveyType"])
                                ->globalSearch($request->all());

            return response()->success('Survey entries are retrieved successfully', $surveyEntries, 200);

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
    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $surveyEntry = MntSurveyEntry::create($input);

            return response()->success('Survey entry created successfully', $surveyEntry, 201);

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

            $survey = MntSurveyEntry::with(["opsVessel","mntSurvey"])->find($id);

            return response()->success('Survey entry type found successfully', $survey, 200);

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
    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();

            $survey = MntSurveyEntry::findorfail($id);
            $survey->update($input);

            return response()->success('Survey entry updated successfully', $survey, 202);

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
    public function destroy(MntSurveyEntry $surveyEntry)
    {
        try {            
            DB::beginTransaction();
            $surveyEntry->delete();
            DB::commit();
            return response()->success('Survey entry deleted successfully', $surveyEntry, 204);
            
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->json($surveyEntry->preventDeletionIfRelated(), 422);

        }
    }
}
