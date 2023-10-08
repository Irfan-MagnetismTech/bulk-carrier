<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Maintenance\Entities\MntJob;

class MntJobController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {

            $jobs = MntJob::with(['opsVessel','mntItem','mntShipDepartment'])->paginate(10);

            return response()->success('Jobs retrieved successfully', $jobs, 200);
            
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
            
            $job = MntJob::create($input);
            
            return response()->success('Job created successfully', $job, 201);
            
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
            
            $job = MntJob::find($id);
            
            return response()->success('Job found successfully', $job, 200);
            
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
            
            $job = MntJob::findorfail($id);
            $job->update($input);
            
            return response()->success('Job updated successfully', $job, 202);
            
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
            $job = MntJob::findorfail($id);
            $job->delete();
            
            return response()->success('Item deleted successfully', $job, 204);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
