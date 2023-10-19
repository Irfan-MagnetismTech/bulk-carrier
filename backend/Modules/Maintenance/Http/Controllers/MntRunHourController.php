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

class MntRunHourController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {

            $runHours = MntRunHour::with(['opsVessel:id,name','mntItem.mntItemGroup'])
                        ->whereIn('id',function ($query) {
                            $query->from('mnt_run_hours')
                                ->select(DB::raw("MAX(id)"))
                                ->groupBy('mnt_item_id');
                        })
                        ->when(request()->business_unit != "ALL", function($q){
                            $q->where('business_unit', request()->business_unit);  
                        })
                        ->paginate(10);

            return response()->success('Run hours retrieved successfully', $runHours, 200);
            
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

            $mntItem = new MntItem();
            // determine the items require to update 
            if ($input['mnt_item_id'][0] == "all") {
                $removedItem = array_shift($input['itemGroupWiseHourlyItems']); // remove the first item which contains "all" to get all other items
                $mntItemIds = $input['itemGroupWiseHourlyItems'];
            } else {
                $mntItemIds = array_filter($input['itemGroupWiseHourlyItems'], function($item) use($input){
                    return in_array($item['id'], $input['mnt_item_id']);
                });
            }


            //foreach item 
            foreach ($mntItemIds as $mntItemId) {
                $presentRunHour = $mntItemId['present_run_hour'] + $input['present_run_hour'];
                //update present run hour = previous run hour + present run hour
                $mntItem->find($mntItemId['id'])->update(['present_run_hour' => $presentRunHour]);

                // create run hour record
                $runHour['ops_vessel_id'] = $input['ops_vessel_id'];
                $runHour['mnt_item_id'] = $mntItemId['id'];
                $runHour['previous_run_hour'] = $mntItemId['present_run_hour'];
                $runHour['present_run_hour'] = $input['present_run_hour'];
                $runHour['updated_on'] = $input['updated_on'];
                $runHour['business_unit'] = $input['business_unit'];

                $mntRunHour = MntRunHour::create($runHour);
            }
            
            return response()->success('Run hour updated successfully', $mntRunHour, 201);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function updateNextDue(Request $request)
    {
        $itemArray = json_decode($request->get('itemArray'));
        $mntItem = new MntItem();
        foreach ($itemArray as $itemId) {
            $mntItem->find($itemId)->mntJobLines()->decrement('next_due', 5);
        }
        $jobs = MntJob::with(['mntJobLines'])->whereIn('mnt_item_id', $itemArray)->get();
        return response()->success('Run hour updated successfully', $jobs, 201);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        try {
            
            $job = MntRunHour::with(['opsVessel:id,name','mntItem.mntShipDepartment','mntItem.mntItemGroup'])->find($id);
            
            return response()->success('Item run hour found successfully', $job, 200);
            
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

            $mntItem = new MntItem();
            
            $presentRunHour = $input['previous_run_hour'] + $input['present_run_hour'];
            //update present run hour = previous run hour + present run hour
            $mntItemUpdate = $mntItem->find($input['mnt_item_id'])->update(['present_run_hour' => $presentRunHour]); // mnt_items updated
            $mntRunHour = MntRunHour::find($id)->update(['present_run_hour' => $input['present_run_hour']]); // mnt_run_hours updated

            return response()->success('Run hour updated successfully', $mntRunHour, 201);
            
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
        //
    }
}
