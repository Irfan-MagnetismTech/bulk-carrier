<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Maintenance\Entities\MntCriticalFunction;
use Modules\Maintenance\Http\Requests\MntCriticalFunctionRequest;

class MntCriticalFunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {

            $criticalFunctions = MntCriticalFunction::select('*')->paginate(10);

            return response()->success('Critical functions are retrieved successfully', $criticalFunctions, 200);
            
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
    public function mntCriticalFunctions()
    {
        try {

            $criticalFunctions = MntCriticalFunction::select('*')->get();

            return response()->success('Critical functions are retrieved successfully', $criticalFunctions, 200);
            
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
    public function store(MntCriticalFunctionRequest $request)
    {
        try {
            $input = $request->all();
            
            $criticalFunction = MntCriticalFunction::create($input);
            
            return response()->success('Critical function created successfully', $criticalFunction, 201);
            
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
            
            $criticalFunction = MntCriticalFunction::find($id);
            
            return response()->success('Critical function found successfully', $criticalFunction, 200);
            
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
    public function update(MntCriticalFunctionRequest $request, $id)
    {
        try {
            $input = $request->all();
            
            $criticalFunction = MntCriticalFunction::findorfail($id);
            $criticalFunction->update($input);
            
            return response()->success('Critical function updated successfully', $criticalFunction, 202);
            
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
            $criticalFunction = MntCriticalFunction::findorfail($id);
            $criticalFunction->delete();
            
            return response()->success('Critical function deleted successfully', $criticalFunction, 204);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
