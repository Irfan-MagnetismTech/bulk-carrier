<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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

            // $jobs = MntJob::with(['mntItem.mntItemGroup','mntJobLines'])
            //                 ->where('ops_vessel_id', request()->ops_vessel_id)          
            //                 ->whereHas('mntItem.mntItemGroup', function ($query) use ($mntShipDepartmentId) {
            //                         $query->where('mnt_ship_department_id', $mntShipDepartmentId);
            //                     })
            //                 ->get();
            $jobs = MntItemGroup::with(['mntItems.mntJobs.mntJobLines','mntItems.mntJobs' => function($q){
                                    $q->where('ops_vessel_id', request()->ops_vessel_id);
                                }])
                                ->where('mnt_ship_department_id', $mntShipDepartmentId)
                                ->get();


            // $jobs = MntJob::whereHas('mntItem.mntItemGroup', function ($query) use ($mntShipDepartmentId) {
            //     $query->where('mnt_ship_department_id', $mntShipDepartmentId);
            // })->with('mntJobLines')->get();
                            
            
            // To get the return value dynamically
            // $returnField = request()->return_field ?? '';
            // if ($returnField == "mntItem" || $returnField == "mntJobLines") {
            //     $jobs = $jobs->pluck($returnField)->flatten();
            // }
            
            return $jobs;
            
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

            $jobs = MntItemGroup::with(['mntItems.mntJobs.mntJobLines','mntItems.mntJobs' => function($q){
                                    $q->where('ops_vessel_id', request()->ops_vessel_id);
                                }])
                                ->where('mnt_ship_department_id', $mntShipDepartmentId)
                                ->get()->toArray();
            // $jobs = $jobs->values();
            print_r($jobs);exit;
            // $jobs =  $jobs->filter(function ($value) {
                // var_dump($value->mntItems->mntJobs);exit;
                // return $value->upcoming_job;
            // });
            $jobs = array_filter($jobs, function ($job) {
                return $job["mntItems"]["mntJobs"]["mntJobLines"]["upcoming_job"];
            });
            
            return $jobs;
            
        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

}
