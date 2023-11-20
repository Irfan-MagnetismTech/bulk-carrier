<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Maintenance\Entities\MntJobLine;
use Modules\Maintenance\Entities\MntWorkRequisition;
use Modules\Maintenance\Entities\MntWorkRequisitionItem;
use Modules\Maintenance\Entities\MntWorkRequisitionLine;
use Modules\Maintenance\Http\Requests\MntWorkRequisitionRequest;

class MntWorkRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {
            $runHours = MntWorkRequisition::with(['opsVessel:id,name','mntWorkRequisitionItem.mntItem'])
                        ->where('status', 0)
                        ->globalSearch($request->all());

            return response()->success('Work requisitions retrieved successfully', $runHours, 200);
            
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
    public function indexWip(Request $request)
    {
        try {
            $runHours = MntWorkRequisition::with(['opsVessel:id,name','mntWorkRequisitionItem.mntItem'])
                        ->where('status', request()->status ?? 1 )
                        ->globalSearch($request->all());

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
    public function store(MntWorkRequisitionRequest $request)
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
            // Store work requisition
            $workRequisition = MntWorkRequisition::create($wr);
            // Store work requisition item information
            $workRequisitionItem = $workRequisition->mntWorkRequisitionItem()
                                                   ->create([
                                                        "mnt_item_id" => $input['mnt_item_id'],
                                                        "present_run_hour" => $input['present_run_hour']
                                                    ]);
            $added_job_lines = array();
            foreach ($input['added_job_lines'] as $added_job_line) {
                $added_job_line['present_run_hour'] = $input['present_run_hour'];
                $added_job_lines[] = $added_job_line;
            }
            // Store work requisition jobs
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
                'mntWorkRequisitionLines',
                'mntWorkRequisitionMaterials'
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
    public function update(MntWorkRequisitionRequest $request, $id)
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
            $workRequisitionItem->update([
                                    "mnt_item_id" => $input['mnt_item_id'],
                                    "present_run_hour" => $input['present_run_hour']
                                ]);
            $addedJobLines = $input['added_job_lines'];
            
            $workRequisitionItem->mntWorkRequisitionLines()->delete();
            $workRequisitionLines = $workRequisitionItem->mntWorkRequisitionLines()->createMany($addedJobLines);
            if ($workRequisitionLines == null) {
                $error = array(
                    "message" => "Data could not be updated!",
                    "errors" => [
                        "added_job_lines"=>["This data could not be updated!"]
                    ]
                );
                // DB::rollBack();
                return response()->json($error, 422);
            }
            DB::commit();
            return response()->success('Work requisition updated successfully', $workRequisition, 202);
            
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function updateWip(Request $request, $id)
    {
        try {
            $input = $request->all();

            $wr['act_start_date'] = $input['act_start_date'];
            $wr['act_completion_date'] = $input['act_completion_date'];
            $wr['status'] = ($input['act_completion_date'] == '') ? 1: 2;

            DB::beginTransaction();

            $workRequisition = MntWorkRequisition::findorfail($id);
            $workRequisition->update($wr);
            
            $workRequisitionLines = $workRequisition->mntWorkRequisitionMaterials()->createUpdateOrDelete($input['mntWorkRequisitionMaterials']);
            
            DB::commit();
            return response()->success('Work requisition updated successfully', $workRequisition, 202);
            
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function updateWipLine(Request $request, $id)
    {
        try {
            $validated = $request->validate( [
                'start_date' => ['required']
            ]);
            $input = $request->all();

            if (isset($input['completion_date']) && $input['completion_date'] != "") {
                $startDate = strtotime($input['start_date']);
                $completionDate = strtotime($input['completion_date']);
                if ($startDate > $completionDate) {
                    $error = array(
                        "message" => "Completion date should be after start date.",
                        "errors" => [
                            "completion_date" => ["Completion date should be after start date."]
                        ]
                    );
                    return response()->json($error, 422);
                }
            }

            $wr['start_date'] = $input['start_date'];
            $wr['completion_date'] = $job['last_done'] = $input['completion_date'];
            $job['previous_run_hour'] = $input['present_run_hour']; // Present run hour is previous run hour for next job
            $wr['checking'] = $input['checking'] ?? 0;
            $wr['replace'] = $input['replace'] ?? 0;
            $wr['cleaning'] = $input['cleaning'] ?? 0;
            $wr['remarks'] = $input['remarks'];
            $wr['status'] = ($input['start_date'] == '' || $input['start_date'] == null) 
                                ? 0 : (
                                    ($input['completion_date'] == '' || $input['completion_date'] == null) 
                                        ? 1 : 2
                                    );

            DB::beginTransaction();

            $workRequisition = MntWorkRequisitionLine::findorfail($id);
            $workRequisition->update($wr);

            $jobLine = MntJobLine::findorfail($input['mnt_job_line_id']);
            $jobLine->update($job);
            
            DB::commit();
            return response()->success('Work requisition updated successfully', $workRequisition, 202);
            
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
            $wr = MntWorkRequisition::where(['id'=>$id, 'status'=>'0'])->first();
            if (empty($wr)) {
                $error = array(
                    "message" => "The requisition could not be deleted",
                    "errors" => [
                        
                    ]
                );
                return response()->json($error, 422);
            } else {
                $wr->mntWorkRequisitionLines()->delete();
                $wr->mntWorkRequisitionItem()->delete();
                $wr->delete();
                
                DB::commit();
                return response()->success('Job deleted successfully', $wr, 204);
            }
            
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }
}
