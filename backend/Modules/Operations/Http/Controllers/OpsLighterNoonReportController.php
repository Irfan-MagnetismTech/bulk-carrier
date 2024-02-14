<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsLighterNoonReport;
use Modules\Operations\Http\Requests\OpsLighterNoonReportRequest;

class OpsLighterNoonReportController extends Controller
{
    use HasRoles;
  
    function __construct(private FileUploadService $fileUpload)
    {
        $this->middleware('permission:ops-lighterage-noon-report-create|ops-lighterage-noon-report-edit|ops-lighterage-noon-report-view|ops-lighterage-noon-report-delete', ['only' => ['index','show']]);
        $this->middleware('permission:ops-lighterage-noon-report-create', ['only' => ['store']]);
        $this->middleware('permission:ops-lighterage-noon-report-edit', ['only' => ['update']]);
        $this->middleware('permission:ops-lighterage-noon-report-delete', ['only' => ['destroy']]);
    }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
    public function index(Request $request) : JsonResponse
    {
        try {
            $lighterNoonReports = OpsLighterNoonReport::with('opsVessel','opsVoyage.opsCargoType','opsBunkers')
            ->globalSearch($request->all());
            
            return response()->success('Data retrieved successfully.', $lighterNoonReports, 200);
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
        try {
            DB::beginTransaction();
            $lighterNoonReportInfo = $request->except(
                '_token',
                'opsBunkers',
            );
            
            // if($request->last_port == $request->next_port){
            //     $error= [
            //         'message'=>'Last Port and Next Port can not be same.',
            //         'errors'=>[
            //             'next_port'=>['Last Port and Next Port can not be same.',
            //     ]]];
            //     return response()->json($error, 422);
            // }

            $lighter_noon_report = OpsLighterNoonReport::create($lighterNoonReportInfo);
            $lighter_noon_report->opsBunkers()->createMany($request->opsBunkers);
            DB::commit();
            return response()->success('Data added successfully.', $lighter_noon_report, 201);
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
            return response()->success('Data retrieved successfully.', $lighter_noon_report, 200);
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

            // if($request->last_port == $request->next_port){
            //     $error= [
            //             'message'=>'Last Port and Next Port can not be same.',
            //             'errors'=>[
            //                 'next_port'=>['Last Port and Next Port can not be same.',
            //     ]]];
            //     return response()->json($error, 422);
            // }
        
            $lighter_noon_report->update($lighterNoonReportInfo);            
            $lighter_noon_report->opsBunkers()->delete();
            $lighter_noon_report->opsBunkers()->createMany($request->opsBunkers);
            DB::commit();
            return response()->success('Data updated successfully.', $lighter_noon_report, 202);
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
            DB::beginTransaction();
            $lighter_noon_report->opsBunkers()->delete();
            $lighter_noon_report->delete();
            DB::commit();

            return response()->json([
                'message' => 'Data deleted successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }
     
}
