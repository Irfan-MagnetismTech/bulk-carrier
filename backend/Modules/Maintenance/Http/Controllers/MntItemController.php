<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Maintenance\Entities\MntItem;
use Modules\Maintenance\Entities\MntItemGroup;
use Modules\Maintenance\Http\Requests\MntItemRequest;
use Modules\Maintenance\Http\Requests\MntItemUpdateRequest;

class MntItemController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {

            $item = MntItem::with(['mntItemGroup.mntShipDepartment'])
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->paginate(10);

            return response()->success('Items retrieved successfully', $item, 200);
            
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
     * @param MntItemRequest $request
     * @return Renderable
     */
    public function store(MntItemRequest $request)
    {
        try {
            $input = $request->all();

            $input['description'] = json_encode($input['description']);
            
            $item = MntItem::create($input);
            
            return response()->success('Item created successfully', $item, 201);
            
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
            
            $item = MntItem::with(['mntItemGroup.mntShipDepartment.mntItemGroups'])->find($id);

            $item['description'] = json_decode($item['description']);
            
            return response()->success('Item found successfully', $item, 200);
            
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
     * @param MntItemRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(MntItemRequest $request, $id)
    {
        try {
            $input = $request->all();
            $input['description'] = json_encode($input['description']);
            
            $item = MntItem::findorfail($id);
            $item->update($input);
            
            return response()->success('Item updated successfully', $item, 202);
            
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
            $item = MntItem::findorfail($id);
            $item->delete();
            
            return response()->success('Item deleted successfully', $item, 204);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
    
    /**
     * Get the item code.
     * 
     */
    public function getMntItemCode($mntItemGroupId)
    {
        
        try {

            $lastItem = MntItem::where('mnt_item_group_id', $mntItemGroupId)->latest()->first();
            $lastItemCode = $lastItem->item_code;
            $lastItemCodeSplit = explode('-', $lastItemCode);
            $itemGroup = MntItemGroup::where('id', $mntItemGroupId)->first();
            $itemCode = $itemGroup->short_code.'-'.str_pad($lastItemCodeSplit[1]+1, 3, '0', STR_PAD_LEFT);
            return response()->success('Item code retrieved successfully', $itemCode, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getMntShipDepartmentWiseItems($mntShipDepartmentId)
    {
        
        try {

            $items = MntItem::select('id','name','item_code')
                    ->whereIn('mnt_item_group_id', function($query) use($mntShipDepartmentId){
                        $query->from('mnt_item_groups')
                                ->select('id')
                                ->where('mnt_ship_department_id', $mntShipDepartmentId);
                    })
                    ->get();
            return response()->success('Items retrieved successfully', $items, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Get item group wise items
     * @param $mntItemGroupId
     */
    public function getMntItemGroupWiseItems($mntItemGroupId)
    {
        
        try {

            $items = MntItem::where(['mnt_item_group_id'=>$mntItemGroupId])->get();
            return response()->success('Items retrieved successfully', $items, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Get item group wise items which has run hour
     * @param $mntItemGroupId
     */
    public function getMntItemGroupWiseHourlyItems($mntItemGroupId)
    {
        
        try {

            $items = MntItem::select('id','name','item_code')->where(['mnt_item_group_id'=>$mntItemGroupId, 'has_run_hour'=>true])->get();
            return response()->success('Items retrieved successfully', $items, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
