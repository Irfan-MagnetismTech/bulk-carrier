<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Maintenance\Entities\MntWorkRequisition;
use Modules\Maintenance\Entities\MntWorkRequisitionItem;

class MntWorkRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $runHours = MntWorkRequisition::with(['opsVessel:id,name','mntWorkRequisitionItem.mntItem'])
                        ->when(request()->business_unit != "ALL", function($q){
                            $q->where('business_unit', request()->business_unit);  
                        })
                        ->latest()
                        ->paginate(10);

            return response()->success('Work requisitions retrieved successfully', $runHours, 200);
            
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

            $wr['reference_no'] = $input['reference_no'];
            $wr['ops_vessel_id'] = $input['ops_vessel_id'];
            $wr['assigned_to'] = $input['assigned_to'];
            $wr['responsible_person'] = $input['responsible_person'];
            $wr['maintenance_type'] = $input['maintenance_type'];
            $wr['requisition_date'] = $input['requisition_date'];
            $wr['est_start_date'] = $input['est_start_date'];
            $wr['est_completion_date'] = $input['est_completion_date'];
            $wr['business_unit'] = $input['business_unit'];

            DB::beginTransaction();
            $workRequisition = MntWorkRequisition::create($wr);

            $workRequisitionItem = $workRequisition->mntWorkRequisitionItem()->create(["mnt_item_id"=>$input['mnt_item_id']]);
            $added_job_lines = array();
            foreach ($input['added_job_lines'] as $added_job_line) {
                $added_job_line['present_run_hour'] = $input['present_run_hour'];
                $added_job_lines[] = $added_job_line;
            }

            $workRequisitionLines = $workRequisitionItem->mntWorkRequisitionLines()->createMany($added_job_lines);
            
            DB::commit();
            return response()->success('Work requisition created successfully', $workRequisitionLines, 201);
            
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
            
            $wr = MntWorkRequisition::with([
                'opsVessel',
                'mntWorkRequisitionItem',
                'mntWorkRequisitionItem.MntItem.MntItemGroup.MntShipDepartment.MntItemGroups.MntItems',
                'mntWorkRequisitionLines'
                ])->find($id);
            
                        
            $results = [];
            array_walk_recursive($wr->mntWorkRequisitionLines, function ($item, $key) use (&$results){$results[$key] = $item;});
            // var_dump($results);
            return response()->success('Work requisition found successfully', $wr, 200);
            
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

            $wr['reference_no'] = $input['reference_no'];
            $wr['ops_vessel_id'] = $input['ops_vessel_id'];
            $wr['assigned_to'] = $input['assigned_to'];
            $wr['responsible_person'] = $input['responsible_person'];
            $wr['maintenance_type'] = $input['maintenance_type'];
            $wr['requisition_date'] = $input['requisition_date'];
            $wr['est_start_date'] = $input['est_start_date'];
            $wr['est_completion_date'] = $input['est_completion_date'];
            $wr['business_unit'] = $input['business_unit'];
            $wr['status'] = $input['status'];

            DB::beginTransaction();

            $workRequisition = MntWorkRequisition::findorfail($id);
            $workRequisition->update($wr);

            $workRequisitionItem = MntWorkRequisitionItem::where('mnt_work_requisition_id',$workRequisition->id)->first();
            $workRequisitionItem->update(["mnt_item_id"=>$input['mnt_item_id']]);
            
            $workRequisitionLines = $workRequisitionItem->mntWorkRequisitionLines()->createUpdateOrDelete($input['added_job_lines']);
            
            DB::commit();
            return response()->success('Work requisition updated successfully', $workRequisitionLines, 201);
            
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
        //
    }
}
