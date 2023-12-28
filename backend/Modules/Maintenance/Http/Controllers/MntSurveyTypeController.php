<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Maintenance\Entities\MntSurveyType;
use Modules\Maintenance\Http\Requests\MntSurveyTypeRequest;

class MntSurveyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {

            $surveyTypes = MntSurveyType::select('*')
                                ->globalSearch($request->all());

            return response()->success('Survey types are retrieved successfully', $surveyTypes, 200);
            
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
    public function MntSurveyTypes()
    {
        try {

            $surveyTypes = MntSurveyType::select('*')->get();

            return response()->success('Survey types are retrieved successfully', $surveyTypes, 200);
            
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
    public function store(MntSurveyTypeRequest $request)
    {
        try {
            $input = $request->all();
            
            $surveyType = MntSurveyType::create($input);
            
            return response()->success('Survey type created successfully', $surveyType, 201);
            
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
            
            $surveyType = MntSurveyType::find($id);
            
            return response()->success('Survey type found successfully', $surveyType, 200);
            
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
    public function update(MntSurveyTypeRequest $request, $id)
    {
        try {
            $input = $request->all();
            
            $surveyType = MntSurveyType::findorfail($id);
            $surveyType->update($input);
            
            return response()->success('Survey type updated successfully', $surveyType, 202);
            
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
    public function destroy($id)
    {
        try {            
            $surveyType = MntSurveyType::findorfail($id);
            if ($surveyType->mntSurvey()->count() > 0) {
                $error = array(
                    "message" => "Data could not be deleted!",
                    "errors" => [
                        "id"=>["This data could not be deleted as it is in use."]
                    ]
                );
                return response()->json($error, 422);
            }
            $surveyType->delete();
            
            return response()->success('Survey type deleted successfully', $surveyType, 204);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
