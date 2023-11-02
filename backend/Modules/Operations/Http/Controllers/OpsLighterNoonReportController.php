<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsLighterNoonReport;
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
            $lighterNoonReports = OpsLighterNoonReport::with('opsVessel','opsVoyage','opsBunkers')->latest()->paginate(15);
            
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
         // dd($request);
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
         $lighter_noon_report->load('opsVessel','opsVoyage','opsBunkers');
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
             return response()->success('Lighter noon report updated successfully.', $lighter_noon_report, 200);
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
     
}
