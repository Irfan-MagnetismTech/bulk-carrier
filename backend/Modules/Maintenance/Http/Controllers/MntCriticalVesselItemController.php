<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Maintenance\Entities\MntCriticalFunction;
use Modules\Maintenance\Entities\MntCriticalVesselItem;
use Modules\Maintenance\Http\Requests\MntCriticalVesselItemRequest;

class MntCriticalVesselItemController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {

            $jobs = MntCriticalVesselItem::with(['opsVessel:id,name','mntCriticalItem.mntCriticalItemCat.mntCriticalFunction'])
                    ->globalSearch($request->all());

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
            $vesselItem['specification'] = $request->get('specification');
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
            
            $mntCriticalVesselItem = MntCriticalVesselItem::with(['opsVessel:id,name','mntCriticalItem.mntCriticalItemCat.mntCriticalFunction.mntCriticalItemCats','mntCriticalItem.mntCriticalItemCat.mntCriticalItems','mntCriticalItemSps'])->find($id);
            
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
            $vesselItem['specification'] = $request->get('specification');
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
    public function destroy(MntCriticalVesselItem $criticalVesselItem)
    {
        try {
            DB::beginTransaction();
            
            // Delete critical item spare parts
            $criticalVesselItem->mntCriticalItemSps()->delete();
            // Delete critical item
            $criticalVesselItem->delete();
            
            DB::commit();
            return response()->success('Critical vessel item deleted successfully', $criticalVesselItem, 422);
            
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->json($criticalVesselItem->preventDeletionIfRelated(), 422);

        }
    }


    public function getCriticalVesselItems() 
    {
        try {

            $criticalVesselItems = MntCriticalVesselItem::with(['mntCriticalItem','mntCriticalItemSps'])            
                                ->when(request()->has('ops_vessel_id'), function($q){
                                    $q->where('ops_vessel_id', request()->ops_vessel_id); 
                                })
                                ->where('is_critical', 1)
                                ->get();

            return response()->success('Critical vessel items are retrieved successfully', $criticalVesselItems, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCriticalVesselFunctions() 
    {
        $opsVesselId = request()->ops_vessel_id;
        try {
            $criticalVesselFunctions = MntCriticalFunction::with([
                                        'mntCriticalItemCats.mntCriticalItems' => function ($qry) use ($opsVesselId) {
                                            // Checking if the item is assigned to the vessel
                                            $qry->whereHas('mntCriticalVesselItems', function ($q) use ($opsVesselId) {
                                                $q->select('mnt_critical_item_id', DB::raw('count(mnt_critical_item_id) as total_items'))
                                                ->where('ops_vessel_id', $opsVesselId)
                                                ->groupBy('mnt_critical_item_id')
                                                ->havingRaw('count(mnt_critical_item_id) > ?', [0]);
                                            });
                                        },
                                        'mntCriticalItemCats.mntCriticalItems.mntCriticalVesselItems' => function($query) use ($opsVesselId) {
                                            $query->where('ops_vessel_id', $opsVesselId);
                                        }
                                        ])
                                        ->whereHas('mntCriticalItemCats.mntCriticalItems', function($q){
                                            // Check if the function has items
                                            $q->select('item_name', DB::raw('count(item_name) as total_items'))
                                                ->groupBy('item_name')
                                                ->havingRaw('count(item_name) > ?', [0]);
                                        })
                                        ->get();

            return response()->success('Critical vessel functions are retrieved successfully', $criticalVesselFunctions, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }
}
