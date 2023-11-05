<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Maintenance\Entities\MntItem;
use Modules\Maintenance\Entities\MntJob;
use Modules\Maintenance\Entities\MntRunHour;
use Modules\Maintenance\Http\Requests\MntJobRequest;

class MntJobController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {

            $jobs = MntJob::with(['opsVessel:id,name','mntItem.mntItemGroup.mntShipDepartment'])
                        ->when(request()->business_unit != "ALL", function($q){
                            $q->where('business_unit', request()->business_unit);  
                        })
                        ->paginate(10);

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
    public function store(MntJobRequest $request)
    {
        try {
            $jobInput['ops_vessel_id'] = $runHourInput['ops_vessel_id'] = $request->get('ops_vessel_id');
            $jobInput['mnt_item_id'] = $runHourInput['mnt_item_id'] = $request->get('mnt_item_id');
            $jobInput['present_run_hour'] = $runHourInput['present_run_hour'] = $request->get('present_run_hour') ?? '';
            $jobInput['business_unit'] = $runHourInput['business_unit'] = $request->get('business_unit');

            $jobLines = $request->get('mntJobLines');
            
            DB::beginTransaction();
            $job = MntJob::create($jobInput); // Create job
            $job->mntJobLines()->createMany($jobLines); // create relevant job lines

            $mntItem = MntItem::where('id', $jobInput['mnt_item_id'])->first();
            // Create run hour entry if the item has run hour
            if ($mntItem->has_run_hour == true) {
                $runHourInput['updated_on'] = date('Y-m-d', strtotime($mntItem->updated_at));
                $runHour = MntRunHour::create($runHourInput);
            }
            
            DB::commit();
            return response()->success('Job created successfully', $job, 201);
            
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
            
            $job = MntJob::with(['opsVessel:id,name','mntItem.mntItemGroup.mntShipDepartment.mntItemGroups','mntItem.mntItemGroup.mntItems','mntJobLines'])->find($id);
            
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
    public function update(MntJobRequest $request, $id)
    {
        try {
            // var_dump($request->all());
            $jobInput['ops_vessel_id'] = $request->get('ops_vessel_id');
            $jobInput['mnt_item_id'] = $request->get('mnt_item_id');
            $jobInput['business_unit'] = $request->get('business_unit');

            $jobLines = $request->get('mntJobLines');
            
            DB::beginTransaction();
            $job = MntJob::findorfail($id);
            $job->update($jobInput);
            $job->mntJobLines()->createUpdateOrDelete($jobLines);
            
            DB::commit();
            return response()->success('Job updated successfully', $job, 202);
            
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
            $job = MntJob::findorfail($id);
            $job->mntJobLines()->delete();
            $job->delete();
            
            $runHour = MntRunHour::where(['ops_vessel_id'=>$job->ops_vessel_id, 'mnt_item_id'=>$job->mnt_item_id])->delete();

            DB::commit();
            return response()->success('Job deleted successfully', $job, 204);
            
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getItemPresentRunHour($opsVesselId, $mntItemId)
    {
        try {

            $item = MntJob::where(['mnt_item_id'=>$mntItemId, 'ops_vessel_id'=>$opsVesselId])
                    ->first(['present_run_hour as previous_run_hour']);
            return response()->success('Item retrieved successfully', $item, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
