<?php

namespace Modules\Operations\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Modules\Operations\Entities\OpsVessel;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsLighterNoonReport;
use Modules\Operations\Http\Exports\LighterNoonReportExport;
use Modules\Operations\Http\Requests\OpsLighterNoonReportRequest;

class OpsLighterNoonReportController extends Controller
{
  // use HasRoles;
  
//    function __construct(private FileUploadService $fileUpload)
//    {
   //     $this->middleware('permission:lighter-noon-report-create|lighter-noon-report-edit|lighter-noon-report-show|lighter-noon-report-delete', ['only' => ['index','show']]);
   //     $this->middleware('permission:lighter-noon-report-create', ['only' => ['store']]);
   //     $this->middleware('permission:lighter-noon-report-edit', ['only' => ['update']]);
   //     $this->middleware('permission:lighter-noon-report-delete', ['only' => ['destroy']]);
//    }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
    public function index()
    {
        try {
            $lighterNoonReports = OpsLighterNoonReport::with('opsVessel','opsVoyage.opsCargoType','opsBunkers')->latest()->paginate(15);
            
            return response()->success('Successfully retrieved lighter noon reports.', $lighterNoonReports, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
 
 
    /**
     * Store a newly created resource in storage.
    * 
    * @param OpsLighterNoonReportRequest $request
    * @return JsonResponse
    */
    public function store(OpsLighterNoonReportRequest $request): JsonResponse
    {
    //  dd($request);
        try {
            DB::beginTransaction();
            $lighterNoonReportInfo = $request->except(
                '_token',
                'opsBunkers',
            );

            $lighter_noon_report = OpsLighterNoonReport::create($lighterNoonReportInfo);
            $lighter_noon_report->opsBunkers()->createMany($request->opsBunkers);
            DB::commit();
            return response()->success('Lighter noon report added successfully.', $lighter_noon_report, 201);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }
 
    /**
     * Display the specified maritime certification.
    *
    * @param  OpsLighterNoonReport  $lighter_noon_report
    * @return JsonResponse
    */
    public function show(OpsLighterNoonReport $lighter_noon_report): JsonResponse
    {
        $lighter_noon_report->load('opsVessel','opsVoyage','opsBunkers', 'lastPort', 'nextPort');
        $lighter_noon_report->opsBunkers->map(function($bunker) {
            $bunker->name = $bunker->scmMaterial->name;
            return $bunker;
        });

        try
        {
            return response()->success('Successfully retrieved lighter noon report.', $lighter_noon_report, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }
 
 
    /**
     * Update the specified resource in storage.
    *
    * @param OpsLighterNoonReportRequest $request
    * @param  OpsLighterNoonReport  $lighter_noon_report
    * @return JsonResponse
    */
    public function update(OpsLighterNoonReportRequest $request, OpsLighterNoonReport $lighter_noon_report): JsonResponse
    {

        try {
            DB::beginTransaction();
            $lighterNoonReportInfo = $request->except(
                '_token',
                'opsBunkers',
            );
        
            $lighter_noon_report->update($lighterNoonReportInfo);            
            $lighter_noon_report->opsBunkers()->delete();
            $lighter_noon_report->opsBunkers()->createMany($request->opsBunkers);
            DB::commit();
            return response()->success('Lighter noon report updated successfully.', $lighter_noon_report, 202);
        }
        catch (QueryException $e)
        {            
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified vessel from storage.
    *
    * @param  OpsLighterNoonReport  $lighter_noon_report
    * @return \Illuminate\Http\JsonResponse
    */
    public function destroy(OpsLighterNoonReport $lighter_noon_report): JsonResponse
    {
        try
        {
            $lighter_noon_report->opsBunkers()->delete();
            $lighter_noon_report->delete();

            return response()->json([
                'message' => 'Successfully deleted lighter noon report.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
     

    public function exportLighterNoonReport(Request $request)
    {
        $lighter_noon_reports['data'] = OpsLighterNoonReport::with('opsVessel','opsVoyage.opsCargoType','opsBunkers')
        ->where('id', $request->id)
        ->when(isset(request()->vessel_id), function($q){
            $q->where('ops_vessel_id', request()->vessel_id);  
        })
        ->when(request()->month != null, function($q){
            $q->whereMonth('date', '=', request()->month);  
        })
        ->when(request()->from_date && request()->till_date, function ($query) {
            $query->whereDate('date', '>=', Carbon::parse(request()->from_date))
                  ->whereDate('date', '<=', Carbon::parse(request()->till_date));
        })
        ->get();

        $vessel=null;
        if($request->vessel_id){
            $vessel= OpsVessel::find($request->vessel_id);
        }
        
        if(isset($lighter_noon_reports)){
            $lighter_noon_reports['data']->map(function($lighter_noon_report) {               
                $lighter_noon_report->fuel_con_24h= $lighter_noon_report->opsBunker->sum('fuel_con_24h');
                $lighter_noon_report->fuel_stock_l= $lighter_noon_report->opsBunker->sum('fuel_stock_l');
                return $lighter_noon_report;
            });
        }

        $lighter_noon_reports['vessel_name']=$vessel?->name;

        return Excel::download(new LighterNoonReportExport($lighter_noon_reports), 'LighterNoonReport.xlsx');
        
    }
}
