<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Maintenance\Entities\MntItem;
use Modules\Maintenance\Entities\MntItemGroup;
use Modules\Maintenance\Entities\MntJob;

class MntReportController extends Controller
{
    
    /**
     * Display a list of items with all jobs
     * @return Renderable
     */
    public function reportAllJobs()
    {
        try {
            $mntShipDepartmentId = request()->mnt_ship_department_id; // Replace with the actual ID of the MntShipDepartment
            $opsVesselId = request()->ops_vessel_id; // Replace with the actual ID of the MntShipDepartment

            $mntItemGroups = MntItemGroup::whereHas('mntItems.mntJobs.mntJobLines', function ($query) use ($mntShipDepartmentId, $opsVesselId) {
                $query->whereHas('mntJob.mntItem.mntItemGroup', function ($query) use ($mntShipDepartmentId) {
                    $query->where('mnt_ship_department_id', $mntShipDepartmentId);
                })
                ->where('ops_vessel_id', $opsVesselId);
            })->with(['mntItems' => function ($query) use ($mntShipDepartmentId, $opsVesselId) {
                $query->whereHas('mntJobs.mntJobLines', function ($query) use ($mntShipDepartmentId, $opsVesselId) {
                    $query->whereHas('mntJob.mntItem.mntItemGroup', function ($query) use ($mntShipDepartmentId) {
                        $query->where('mnt_ship_department_id', $mntShipDepartmentId);
                    })
                    ->where('ops_vessel_id', $opsVesselId);
                });
                $query->with(['mntJobs.mntJobLines','mntJobs'=>function ($query) use ($mntShipDepartmentId, $opsVesselId) {
                    $query->where('ops_vessel_id', $opsVesselId);
                }
                ]);
                $query->orderBy('item_code','asc');
            }])->get();

            return response()->success('All jobs are retrieved successfully', $mntItemGroups, 200);
            
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
    
    /**
     * Display a list of items with all jobs
     * @return Renderable
     */
    public function reportUpcomingJobs()
    {
        try {
            $mntShipDepartmentId = request()->mnt_ship_department_id; // Replace with the actual ID of the MntShipDepartment
            $opsVesselId = request()->ops_vessel_id;
            
            // retrieve all jobs of all item groups
            $mntItemGroups = MntItemGroup::whereHas('mntItems.mntJobs.mntJobLines', function ($query) use ($mntShipDepartmentId, $opsVesselId) {
                $query->whereHas('mntJob.mntItem.mntItemGroup', function ($query) use ($mntShipDepartmentId) {
                    $query->where('mnt_ship_department_id', $mntShipDepartmentId);
                })
                ->where('ops_vessel_id', $opsVesselId);
            })->with(['mntItems' => function ($query) use ($mntShipDepartmentId, $opsVesselId) {
                $query->whereHas('mntJobs.mntJobLines', function ($query) use ($mntShipDepartmentId, $opsVesselId) {
                    $query->whereHas('mntJob.mntItem.mntItemGroup', function ($query) use ($mntShipDepartmentId) {
                        $query->where('mnt_ship_department_id', $mntShipDepartmentId);
                    })
                    ->where('ops_vessel_id', $opsVesselId);
                });
                $query->with(['mntJobs.mntJobLines','mntJobs'=>function ($query) use ($mntShipDepartmentId, $opsVesselId) {
                    $query->where('ops_vessel_id', $opsVesselId);
                }
                ]);
                $query->orderBy('item_code','asc');
            }])->get()->toArray();
                       
            // iterate all item groups to cehck if any job is upcoming
            foreach($mntItemGroups as $i => $mntItemGroup){
                $mntItems = $mntItemGroup['mntItems'];
                foreach($mntItems as $j => $mntItem){
                    $mntJobs = $mntItem['mntJobs'];
                    foreach($mntJobs as $k => $mntJob){
                        $mntJobLines = $mntJob['mntJobLines'];
                        
                        $mntJobLines = array_filter($mntJobLines, function($mntJobLine){
                            return $mntJobLine['upcoming_job'] == true;
                        });
                        
                        if(count($mntJobLines) > 0) { // check if there any jobLines left
                            $mntJobs[$k]['mntJobLines'] = array_values($mntJobLines); // if any, reset the job array
                        } else {
                            unset($mntJobs[$k]); // otherwise remove the node
                        }
                    }
                    if(count($mntJobs) > 0) { // check if there is any jobs left
                        $mntItems[$j]['mntJobs'] = $mntJobs; // if any, reset the item array
                    } else {
                        unset($mntItems[$j]); // otherwise remove the node
                    }
                }
                if(count($mntItems) > 0) { // check if there is any item left
                    $mntItemGroups[$i]['mntItems'] = $mntItems;
                } else {
                    unset($mntItemGroups[$i]);
                }
            }
            
            return response()->success('Upcoming jobs are retrieved successfully', $mntItemGroups, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
    
    /**
     * Display a list of items with all jobs
     * @return Renderable
     */
    public function reportOverdueJobs()
    {
        try {
            $mntShipDepartmentId = request()->mnt_ship_department_id; // Replace with the actual ID of the MntShipDepartment
            $opsVesselId = request()->ops_vessel_id;
            
            // Retrieve all jobs for all item groups
            $mntItemGroups = MntItemGroup::whereHas('mntItems.mntJobs.mntJobLines', function ($query) use ($mntShipDepartmentId, $opsVesselId) {
                $query->whereHas('mntJob.mntItem.mntItemGroup', function ($query) use ($mntShipDepartmentId) {
                    $query->where('mnt_ship_department_id', $mntShipDepartmentId);
                })
                ->where('ops_vessel_id', $opsVesselId);
            })->with(['mntItems' => function ($query) use ($mntShipDepartmentId, $opsVesselId) {
                $query->whereHas('mntJobs.mntJobLines', function ($query) use ($mntShipDepartmentId, $opsVesselId) {
                    $query->whereHas('mntJob.mntItem.mntItemGroup', function ($query) use ($mntShipDepartmentId) {
                        $query->where('mnt_ship_department_id', $mntShipDepartmentId);
                    })
                    ->where('ops_vessel_id', $opsVesselId);
                });
                $query->with(['mntJobs.mntJobLines','mntJobs'=>function ($query) use ($mntShipDepartmentId, $opsVesselId) {
                    $query->where('ops_vessel_id', $opsVesselId);
                }
                ]);
                $query->orderBy('item_code','asc');
            }])->get()->toArray();
                        
            //iterate all item groups if there is any job is overdue
            foreach($mntItemGroups as $i => $mntItemGroup){
                $mntItems = $mntItemGroup['mntItems'];
                foreach($mntItems as $j => $mntItem){
                    $mntJobs = $mntItem['mntJobs'];
                    foreach($mntJobs as $k => $mntJob){
                        $mntJobLines = $mntJob['mntJobLines'];
                        
                        $mntJobLines = array_filter($mntJobLines, function($mntJobLine){
                            return $mntJobLine['over_due'] == true;
                        });
                        
                        if(count($mntJobLines) > 0) { // check if there any jobLines left
                            $mntJobs[$k]['mntJobLines'] = array_values($mntJobLines); // if any, reset the job array
                        } else {
                            unset($mntJobs[$k]); // otherwise remove the node
                        }
                    }
                    if(count($mntJobs) > 0) { // check if there is any jobs left
                        $mntItems[$j]['mntJobs'] = $mntJobs; // if any, reset the item array
                    } else {
                        unset($mntItems[$j]); // otherwise remove the node
                    }
                }
                if(count($mntItems) > 0) { // check if there is any item left
                    $mntItemGroups[$i]['mntItems'] = $mntItems;
                } else {
                    unset($mntItemGroups[$i]);
                }
            }
            
            return response()->success('Overdue jobs are retrieved successfully', $mntItemGroups, 200);
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

}
