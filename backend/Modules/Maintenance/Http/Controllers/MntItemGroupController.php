<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Maintenance\Entities\MntItemGroup;
use Modules\Maintenance\Http\Requests\MntItemGroupRequest;

class MntItemGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {

            $itemGroups = MntItemGroup::with('mntShipDepartment')
            ->globalSearch($request->all())
            ->paginate($request->items_per_page);

            return response()->success('Item groups retrieved successfully', $itemGroups, 200);
            
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
    public function store(MntItemGroupRequest $request)
    {
        try {
            $input = $request->all();
            
            $itemGroup = MntItemGroup::create($input);
            
            return response()->success('Item group created successfully', $itemGroup, 201);
            
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
            
            $itemGroup = MntItemGroup::with('mntShipDepartment')->find($id);
            
            return response()->success('Item group found successfully', $itemGroup, 200);
            
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
    public function update(MntItemGroupRequest $request, $id)
    {
        try {
            $input = $request->all();
            
            $itemGroup = MntItemGroup::findorfail($id);
            $itemGroup->update($input);
            
            return response()->success('Item group updated successfully', $itemGroup, 202);
            
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
            $itemGroup = MntItemGroup::findorfail($id);
            $itemGroup->delete();
            
            return response()->success('Item group deleted successfully', $itemGroup, 204);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
    
    /**
     * Get the item groups without pagination.
     * 
     */
    public function getMntItemGroups()
    {
        
        try {

            $itemGroups = MntItemGroup::select('*')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->get();

            return response()->success('Item groups retrieved successfully', $itemGroups, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Get Mnt Ship Department wise Item Groups
     * @param $mntShipDepartmentId
     * @return array of item groups
     */
    public function getMntShipDepartmentWiseItemGroups($mntShipDepartmentId)
    {
        
        try {

            $itemGroups = MntItemGroup::select('id','name','short_code')->where('mnt_ship_department_id', $mntShipDepartmentId)->get();
            return response()->success('Item groups retrieved successfully', $itemGroups, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
