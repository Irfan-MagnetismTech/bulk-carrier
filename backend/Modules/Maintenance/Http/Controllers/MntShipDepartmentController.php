<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Maintenance\Entities\MntShipDepartment;
use Modules\Maintenance\Http\Requests\MntShipDepartmentRequest;

class MntShipDepartmentController extends Controller
{
    
    /**
     * get all ship departments.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {

            $shipDepartments = MntShipDepartment::select('*')
            ->globalSearch($request->all());

            return response()->success('Ship departments retrieved successfully', $shipDepartments, 200);
            
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
    public function store(MntShipDepartmentRequest $request)
    {
        try {
            $input = $request->all();
            
            $shipDepartment = MntShipDepartment::create($input);
            
            return response()->success('Ship departments created successfully', $shipDepartment, 201);
            
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
            
            $shipDepartment = MntShipDepartment::find($id);
            
            return response()->success('Ship department found successfully', $shipDepartment, 200);
            
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
            
            $shipDepartment = MntShipDepartment::findorfail($id);
            $shipDepartment->update($input);
            
            return response()->success('Ship departments updated successfully', $shipDepartment, 202);
            
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
            $shipDepartment = MntShipDepartment::findorfail($id);
            $shipDepartment->delete();
            
            return response()->success('Ship departments deleted successfully', $shipDepartment, 204);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Get the ship departments without pagination.
     * @return JsonResponse
     */
    public function getMntShipDepartments() : JsonResponse
    {
        
        try {

            $shipDepartments = MntShipDepartment::select('*')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->get();

            return response()->success('Ship departments retrieved successfully', $shipDepartments, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    
}
