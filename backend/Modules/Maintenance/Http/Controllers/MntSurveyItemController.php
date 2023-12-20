<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Maintenance\Entities\MntSurveyItem;
use Modules\Maintenance\Http\Requests\MntSurveyItemRequest;

class MntSurveyItemController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {

            $surveyItems = MntSurveyItem::select('*')
                            ->globalSearch($request->all());

            return response()->success('Survey items are retrieved successfully', $surveyItems, 200);
            
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
    public function mntSurveyItems()
    {
        try {

            $surveyItems = MntSurveyItem::select('*')->get();

            return response()->success('Survey items are retrieved successfully', $surveyItems, 200);
            
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
    public function store(MntSurveyItemRequest $request)
    {
        try {
            $input = $request->all();
            
            $surveyItem = MntSurveyItem::create($input);
            
            return response()->success('Survey item created successfully', $surveyItem, 201);
            
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
            
            $surveyItem = MntSurveyItem::find($id);
            
            return response()->success('Survey item found successfully', $surveyItem, 200);
            
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
    public function update(MntSurveyItemRequest $request, $id)
    {
        try {
            $input = $request->all();
            
            $surveyItem = MntSurveyItem::findorfail($id);
            $surveyItem->update($input);
            
            return response()->success('Survey item updated successfully', $surveyItem, 202);
            
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
            $surveyItem = MntSurveyItem::findorfail($id);
            if ($surveyItem->mntSurvey()->count() > 0) {
                $error = array(
                    "message" => "Data could not be deleted!",
                    "errors" => [
                        "id"=>["This data could not be deleted as it is in use."]
                    ]
                );
                return response()->json($error, 422);
            }
            $surveyItem->delete();
            
            return response()->success('Survey item deleted successfully', $surveyItem, 204);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
