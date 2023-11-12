<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Maintenance\Entities\MntCriticalVesselItem;
use Modules\Maintenance\Http\Requests\MntCriticalVesselItemRequest;

class MntCriticalVesselItemController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {

            $jobs = MntCriticalVesselItem::with(['opsVessel:id,name','mntCriticalItem.mntCriticalItemCat.mntCriticalFunction'])
                        ->when(request()->business_unit != "ALL", function($q){
                            $q->where('business_unit', request()->business_unit);  
                        })
                        ->latest()
                        ->paginate(10);

            return response()->success('Critical items for vessels are retrieved successfully', $jobs, 200);
            
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
    public function store(MntCriticalVesselItemRequest $request)
    {
        try {
            $vesselItem['ops_vessel_id'] = $request->get('ops_vessel_id');
            $vesselItem['mnt_critical_item_id'] = $request->get('mnt_critical_item_id');
            $vesselItem['is_critical'] = $request->get('is_critical') ?? '';
            $vesselItem['notes'] = $request->get('notes');
            $vesselItem['business_unit'] = $request->get('business_unit');

            $mntCriticalItemSps = $request->get('mntCriticalItemSps');
            
            DB::beginTransaction();
            $mntCriticalVesselItem = MntCriticalVesselItem::create($vesselItem); // Create vessel critical item
            $mntCriticalVesselItem->mntCriticalItemSps()->createMany($mntCriticalItemSps); // create critical item spare parts
            
            DB::commit();
            return response()->success('Critical vessel items created successfully', $mntCriticalVesselItem, 201);
            
        }
        catch (\Exception $e)
        {
            DB::rollBack();
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
            
            $mntCriticalVesselItem = MntCriticalVesselItem::with(['opsVessel:id,name','mntCriticalItem.mntCriticalItemCat.mntCriticalFunction','mntCriticalItemSp'])->find($id);
            
            return response()->success('Critical vessel item found successfully', $mntCriticalVesselItem, 200);
            
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
    public function update(MntCriticalVesselItemRequest $request, $id)
    {
        try {
            $vesselItem['ops_vessel_id'] = $request->get('ops_vessel_id');
            $vesselItem['mnt_critical_item_id'] = $request->get('mnt_critical_item_id');
            $vesselItem['is_critical'] = $request->get('is_critical') ?? '';
            $vesselItem['notes'] = $request->get('notes');
            $vesselItem['business_unit'] = $request->get('business_unit');

            $mntCriticalItemSps = $request->get('mntCriticalItemSps');
            
            DB::beginTransaction();
            $mntCriticalVesselItem = MntCriticalVesselItem::findorfail($id);
            // Update critical item
            $mntCriticalVesselItem->update($vesselItem);
            // Update critical item spare parts
            $mntCriticalVesselItem->mntCriticalItemSps()->createUpdateOrDelete($mntCriticalItemSps);
            
            DB::commit();
            return response()->success('Critical vessel item updated successfully', $mntCriticalVesselItem, 202);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
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
            DB::beginTransaction();
            $mntCriticalVesselItem = MntCriticalVesselItem::findorfail($id);
            // Delete critical item spare parts
            $mntCriticalVesselItem->mntCriticalItemSps()->delete();
            // Delete critical item
            $mntCriticalVesselItem->delete();
            
            DB::commit();
            return response()->success('Critical vessel item deleted successfully', $mntCriticalVesselItem, 204);
            
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }
}