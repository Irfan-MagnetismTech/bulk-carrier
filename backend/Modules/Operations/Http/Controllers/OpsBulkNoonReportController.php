<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsBulkNoonReport;
use Modules\Operations\Http\Requests\OpsBulkNoonReportRequest;

class OpsBulkNoonReportController extends Controller
{
   // use HasRoles;
   
   
//    function __construct(private FileUploadService $fileUpload)
//    {
   //     $this->middleware('permission:bulk-noon-report-create|bulk-noon-report-edit|bulk-noon-report-show|bulk-noon-report-delete', ['only' => ['index','show']]);
   //     $this->middleware('permission:bulk-noon-report-create', ['only' => ['store']]);
   //     $this->middleware('permission:bulk-noon-report-edit', ['only' => ['update']]);
   //     $this->middleware('permission:bulk-noon-report-delete', ['only' => ['destroy']]);
//    }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
    public function index(Request $request): JsonResponse
    {
        try {
            $bulk_noon_reports = OpsBulkNoonReport::with(['opsVessel','opsVoyage','opsBunkers','opsBulkNoonReportPorts','opsBulkNoonReportCargoTanks','opsBulkNoonReportConsumptions','opsBulkNoonReportDistances','opsBulkNoonReportEngineInputs'])->latest()->paginate(15);
            
            return response()->success('Successfully retrieved bulk noon reports.', $bulk_noon_reports, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
 

    /**
     * Store a newly created resource in storage.
    * 
    * @param OpsBulkNoonReportRequest $request
    * @return JsonResponse
    */
    public function store(OpsBulkNoonReportRequest $request): JsonResponse
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $bulk_noon_report_info = $request->except(
                '_token',
                'opsBunkers',
                'opsBulkNoonReportPorts',
                'opsBulkNoonReportCargoTanks',
                'opsBulkNoonReportConsumptions',
                'opsBulkNoonReportDistances',
                'opsBulkNoonReportEngineInputs'
            );

            $bulk_noon_report = OpsBulkNoonReport::create($bulk_noon_report_info);
            $bulk_noon_report->opsBulkNoonReportPorts()->createMany($request->opsBulkNoonReportPorts);
            $bulk_noon_report->opsBulkNoonReportCargoTanks()->createMany($request->opsBulkNoonReportCargoTanks);
            $bulk_noon_report->opsBulkNoonReportConsumptions()->createMany($request->opsBulkNoonReportConsumptions);
            $bulk_noon_report->opsBulkNoonReportDistances()->createMany($request->opsBulkNoonReportDistances);
            $bulk_noon_report->opsBulkNoonReportEngineInputs()->createMany($request->opsBulkNoonReportEngineInputs);
            $bulk_noon_report->opsBunkers()->createMany($request->opsBunkers);
            DB::commit();
            return response()->success('Bulk noon report added successfully.', $bulk_noon_report, 201);
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
    * @param  OpsBulkNoonReport  $bulk_noon_report
    * @return JsonResponse
    */
    public function show(OpsBulkNoonReport $bulk_noon_report): JsonResponse
    {
        $bulk_noon_report->load(['opsVessel','opsVoyage','opsBunkers','opsBulkNoonReportPorts','opsBulkNoonReportCargoTanks','opsBulkNoonReportConsumptions','opsBulkNoonReportDistances','opsBulkNoonReportEngineInputs']);
        try
        {
            return response()->success('Successfully retrieved bulk noon report.', $bulk_noon_report, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }


    /**
     * Update the specified resource in storage.
    *
    * @param OpsBulkNoonReportRequest $request
    * @param  OpsBulkNoonReport  $bulk_noon_report
    * @return JsonResponse
    */
    public function update(OpsBulkNoonReportRequest $request, OpsBulkNoonReport $bulk_noon_report): JsonResponse
    {
        try {
            DB::beginTransaction();
            $bulk_noon_report_info = $request->except(
                '_token',
                'opsBunkers',
                'opsBulkNoonReportPorts',
                'opsBulkNoonReportCargoTanks',
                'opsBulkNoonReportConsumptions',
                'opsBulkNoonReportDistances',
                'opsBulkNoonReportEngineInputs'
            );
            
            $bulk_noon_report->update($bulk_noon_report_info);

            $bulk_noon_report->opsBulkNoonReportPorts()->delete();
            $bulk_noon_report->opsBulkNoonReportPorts()->createMany($request->opsBulkNoonReportPorts);

            $bulk_noon_report->opsBulkNoonReportCargoTanks()->delete();
            $bulk_noon_report->opsBulkNoonReportCargoTanks()->createMany($request->opsBulkNoonReportCargoTanks);

            $bulk_noon_report->opsBulkNoonReportConsumptions()->delete();
            $bulk_noon_report->opsBulkNoonReportConsumptions()->createMany($request->opsBulkNoonReportConsumptions);

            $bulk_noon_report->opsBulkNoonReportDistances()->delete();
            $bulk_noon_report->opsBulkNoonReportDistances()->createMany($request->opsBulkNoonReportDistances);

            $bulk_noon_report->opsBulkNoonReportEngineInputs()->delete();
            $bulk_noon_report->opsBulkNoonReportEngineInputs()->createMany($request->opsBulkNoonReportEngineInputs);

            $bulk_noon_report->opsBunkers()->delete();
            $bulk_noon_report->opsBunkers()->createMany($request->opsBunkers);
            DB::commit();
            return response()->success('Bulk noon report updated successfully.', $bulk_noon_report, 200);
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
    * @param  OpsBulkNoonReport  $bulk_noon_report
    * @return \Illuminate\Http\JsonResponse
    */
    public function destroy(OpsBulkNoonReport $bulk_noon_report): JsonResponse
    {
        try
        {
            $bulk_noon_report->opsBulkNoonReportPorts()->delete();
            $bulk_noon_report->opsBulkNoonReportCargoTanks()->delete();
            $bulk_noon_report->opsBulkNoonReportConsumptions()->delete();
            $bulk_noon_report->opsBulkNoonReportDistances()->delete();
            $bulk_noon_report->opsBulkNoonReportEngineInputs()->delete();
            $bulk_noon_report->opsBunkers()->delete();
            $bulk_noon_report->delete();

            return response()->json([
                'message' => 'Successfully deleted bulk noon report.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getBulkNoonReportByType(Request $request){
        try {
            $bulk_noon_reports = OpsBulkNoonReport::query()
            ->where(function ($query) use($request) {
                $query->where('type', 'like', '%' . $request->type . '%');                
            })
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->limit(10)
            ->get();

            return response()->success('Successfully retrieved cargo tariffs name.', $bulk_noon_reports, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }
}
