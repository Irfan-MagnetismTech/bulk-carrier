<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Maintenance\Entities\MntCriticalSpList;
use Modules\Maintenance\Http\Requests\MntCriticalSpListRequest;

class MntCriticalSpListController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {

            $criticalSps = MntCriticalSpList::with(['opsVessel:id,name'])->globalSearch($request->all());

            return response()->success('Critical spare list for vessels are retrieved successfully', $criticalSps, 200);
            
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
    public function store(MntCriticalSpListRequest $request)
    {
        try {
            $criticalSp['ops_vessel_id'] = $request->get('ops_vessel_id');
            $criticalSp['reference_no'] = $request->get('reference_no');
            $criticalSp['record_date'] = $request->get('record_date') ?? '';
            $criticalSp['business_unit'] = $request->get('business_unit');

            $mntCriticalSpListLines = $request->get('mntCriticalSpListLines');
            
            $csll = array();
            $count = 1;
            foreach($mntCriticalSpListLines as $critical_item) {
                foreach($critical_item['mntCriticalItemSps'] as $criticalSps) {
                    $csll[$count]['mnt_critical_item_sp_id'] = $criticalSps['id'];
                    $csll[$count]['min_rob'] = $criticalSps['min_rob'];
                    $csll[$count]['rob'] = array_key_exists("rob", $criticalSps) ? $criticalSps['rob'] : 0;
                    $csll[$count]['remarks'] = array_key_exists("remarks", $criticalSps) ? $criticalSps['remarks'] : "";
                }
                $count++;
            }
            // var_dump($csll);

            
            DB::beginTransaction();
            $mntCriticalSp = MntCriticalSpList::create($criticalSp); // Create vessel critical item
            $mntCriticalSp->mntCriticalSpListLines()->createMany($csll); // create critical item spare parts
            
            DB::commit();
            return response()->success('Critical spare parts list created successfully', $mntCriticalSp, 201);
            
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
            
            $mntCriticalVesselItem = MntCriticalSpList::with(['opsVessel:id,name','mntCriticalVesselItems.mntCriticalItem.mntCriticalItemSps.mntCriticalSpListLines'])->find($id);
            
            return response()->success('Critical spare parts list found successfully', $mntCriticalVesselItem, 200);
            
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
            $criticalSp['ops_vessel_id'] = $request->get('ops_vessel_id');
            $criticalSp['reference_no'] = $request->get('reference_no');
            $criticalSp['record_date'] = $request->get('record_date') ?? '';

            $mntCriticalSpListLines = $request->get('mntCriticalSpListLines');

            DB::beginTransaction();
            $mntCriticalSp = MntCriticalSpList::findorfail($id);
            // Update critical item
            $mntCriticalSp->update($criticalSp);
            // Update critical item spare parts
            $mntCriticalSp->mntCriticalSpListLines()->createUpdateOrDelete($mntCriticalSpListLines);
            
            DB::commit();
            return response()->success('Critical spare parts list updated successfully', $mntCriticalSp, 202);
            
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
            $mntCriticalSp = MntCriticalSpList::findorfail($id);
            // Delete critical item spare parts
            $mntCriticalSp->mntCriticalSpListLines()->delete();
            // Delete critical item
            $mntCriticalSp->delete();
            
            DB::commit();
            return response()->success('Critical spare parts list deleted successfully', $mntCriticalSp, 204);
            
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }
    
}
