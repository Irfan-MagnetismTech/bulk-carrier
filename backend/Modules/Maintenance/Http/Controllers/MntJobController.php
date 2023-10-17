<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
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

            $jobs = MntJob::with(['opsVessel:id,name','mntItem:id,name,item_code'])
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
            $jobInput['business_unit'] = $runHourInput['business_unit'] = $request->get('business_unit');

            $jobLines = $request->get('mnt_job_lines');
            
            $job = MntJob::create($jobInput);
            $job->mntJobLines()->createMany($jobLines);

            $mntItem = MntItem::where('id', $jobInput['mnt_item_id'])->first();
            if ($mntItem->has_run_hour == true) {
                $runHourInput['present_run_hour'] = $mntItem->present_run_hour;
                $runHourInput['updated_on'] = date('Y-m-d', strtotime($mntItem->updated_at));
                $runHour = MntRunHour::create($runHourInput);
            }
            
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
            
            $job = MntJob::with(['opsVessel:id,name','mntItem:id,name,item_code','mntJobLines'])->find($id);
            
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
            // var_dump($request->all());
            $jobInput['ops_vessel_id'] = $request->get('ops_vessel_id');
            $jobInput['mnt_item_id'] = $request->get('mnt_item_id');
            $jobInput['business_unit'] = $request->get('business_unit');

            $jobLines = $request->get('mnt_job_lines');
            
            $job = MntJob::findorfail($id);
            $job->update($jobInput);
            $job->mntJobLines()->createUpdateOrDelete($jobLines);
            
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
            $job->mntJobLines()->delete();
            $job->delete();
            // todo: delete run hour item
            return response()->success('Job deleted successfully', $job, 204);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
