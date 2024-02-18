<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsBulkNoonReport;
use Modules\Operations\Http\Exports\BulkNoonReportExport;
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
            $bulk_noon_reports = OpsBulkNoonReport::with(['opsVessel','opsVoyage','opsBunkers','opsBulkNoonReportPorts','opsBulkNoonReportCargoTanks','opsBulkNoonReportConsumptions.opsBulkNoonReportConsumptionHeads.scmMaterial','opsBulkNoonReportDistance','opsBulkNoonReportEngineInputs'])
            ->globalSearch($request->all());

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
        // dd($request->opsBulkNoonReportEngineInputTypes);
        try {
            DB::beginTransaction();
            $bulk_noon_report_info = $request->except(
                '_token',
                'opsBunkers',
                'opsBulkNoonReportPorts',
                'opsBulkNoonReportCargoTanks',
                'opsBulkNoonReportConsumptions',
                'opsBulkNoonReportDistance',
                'opsBulkNoonReportEngineInputs',
            );


            // if(isset($request->opsBulkNoonReportPorts)){
            //     foreach($request->opsBulkNoonReportPorts as $key=>$data){
            //         if($data['last_port'] == $data['next_port']){
            //             $error= [
            //                 'message'=>'Last Port and Next Port can not be same for the row is .'.++$key,
            //                 'errors'=>[
            //                     'next_port'=>['Last Port and Next Port can not be same for the row is .'.++$key,
            //             ]]];

            //             return response()->json($error, 422);
            //         }
            //     }

            // }
            
            if(isset($request->opsBulkNoonReportEngineInputs)){
                $engines= [];
                foreach($request->opsBulkNoonReportEngineInputs as $key=>$engine){
                    $engines[]=$engine['engine_unit'];
                }
    
                
                if (count($engines) !== count(array_unique($engines))) {
                    $error= [
                        'message'=>'In Engine Inputs - Engine unit can not be same.',
                        'errors'=>[
                            'engine_unit'=>['In Engine Inputs - Engine unit  can not be same.',]
                            ]
                        ];
                    return response()->json($error, 422);
                }
            }
                
            $bulk_noon_report = OpsBulkNoonReport::create($bulk_noon_report_info);
            if(isset($request->opsBulkNoonReportPorts)){
                $bulk_noon_report->opsBulkNoonReportPorts()->createMany($request->opsBulkNoonReportPorts);
            }
            
            if(isset($request->opsBulkNoonReportCargoTanks)){
                $bulk_noon_report->opsBulkNoonReportCargoTanks()->createMany($request->opsBulkNoonReportCargoTanks);
            }

            if(isset($request->opsBulkNoonReportConsumptions)){
                foreach ($request->opsBulkNoonReportConsumptions as $consumptionData) {
                    $consumption = $bulk_noon_report->opsBulkNoonReportConsumptions()->create($consumptionData);
                    if(isset($consumptionData['opsBulkNoonReportConsumptionHeads'])) {
                        
                        $consumptionHeadData = collect($consumptionData['opsBulkNoonReportConsumptionHeads'])->map(function($item) use($bulk_noon_report) {
                            $item['ops_bulk_noon_report_id'] = $bulk_noon_report->id;
                            return $item;
                        })->toArray();

                        $consumption->opsBulkNoonReportConsumptionHeads()->createMany($consumptionHeadData);
                    }
                }
            }

            if(isset($request->opsBulkNoonReportDistance)){
                $bulk_noon_report->opsBulkNoonReportDistance()->create($request->opsBulkNoonReportDistance);
            }

            if(isset($request->opsBulkNoonReportEngineInputs)){
                $bulk_noon_report->opsBulkNoonReportEngineInputs()->createMany($request->opsBulkNoonReportEngineInputs);
            }

            // if(isset($request->opsBulkNoonReportEngineInputTypes)){
            //     $bulk_noon_report->opsBulkNoonReportEngineInputTypes()->createMany($request->opsBulkNoonReportEngineInputTypes);
            // }

            if(isset($request->opsBunkers)){
                $bulk_noon_report->opsBunkers()->createMany($request->opsBunkers);
            }

            
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
        $bulk_noon_report->load(['opsVessel','opsVoyage','opsBunkers','opsBulkNoonReportPorts.lastPort','opsBulkNoonReportPorts.nextPort','opsBulkNoonReportCargoTanks','opsBulkNoonReportConsumptions.opsBulkNoonReportConsumptionHeads.scmMaterial','opsBulkNoonReportDistance','opsBulkNoonReportEngineInputs']);
        try
        {
            $bulk_noon_report->opsBulkNoonReportConsumptions->map(function($item) {
                $item->name = $item->scmMaterial->name;

                return $item;
            });
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
                'opsBulkNoonReportDistance',
                'opsBulkNoonReportEngineInputs',
            );
            // if(isset($request->opsBulkNoonReportPorts)){
            //     foreach($request->opsBulkNoonReportPorts as $key=>$data){
            //         if($data->last_port == $data->next_port){
            //             $error= [
            //                 'message'=>'Last Port and Next Port can not be same for the row is .'.++$key,
            //                 'errors'=>[
            //                     'next_port'=>['Last Port and Next Port can not be same for the row is .'.++$key,
            //             ]]];

            //             return response()->json($error, 422);
            //         }
            //     }

            // }
            if(isset($request->opsBulkNoonReportEngineInputs)){
                $engines= [];
                foreach($request->opsBulkNoonReportEngineInputs as $key=>$engine){
                    $engines[]=$engine['engine_unit'];
                }
    
                
                if (count($engines) !== count(array_unique($engines))) {
                    $error= [
                        'message'=>'In Engine Inputs - Engine unit can not be same.',
                        'errors'=>[
                            'engine_unit'=>['In Engine Inputs - Engine unit  can not be same.',]
                            ]
                        ];
                    return response()->json($error, 422);
                }
            }

            $bulk_noon_report->update($bulk_noon_report_info);
            
            $bulk_noon_report->opsBulkNoonReportPorts()->delete();
            if(isset($request->opsBulkNoonReportPorts)){
                $bulk_noon_report->opsBulkNoonReportPorts()->createMany($request->opsBulkNoonReportPorts);
            }

            $bulk_noon_report->opsBulkNoonReportCargoTanks()->delete();
            if(isset($request->opsBulkNoonReportCargoTanks)){
                $bulk_noon_report->opsBulkNoonReportCargoTanks()->createMany($request->opsBulkNoonReportCargoTanks);
            }


            if(isset($request->opsBulkNoonReportConsumptions)){
                $bulk_noon_report->opsBulkNoonReportConsumptions()->delete();
                $bulk_noon_report->opsBulkNoonReportConsumptionHeads()->delete();
                // return response()->json($request->opsBulkNoonReportConsumptions);

                foreach ($request->opsBulkNoonReportConsumptions as $consumptionData) {
                    $consumption = $bulk_noon_report->opsBulkNoonReportConsumptions()->create($consumptionData);

                    if(isset($consumptionData['opsBulkNoonReportConsumptionHeads'])) {

                        $consumptionHeadData = collect($consumptionData['opsBulkNoonReportConsumptionHeads'])->map(function($item) use($bulk_noon_report) {
                            $item['ops_bulk_noon_report_id'] = $bulk_noon_report->id;
                            return $item;
                        })->toArray();

                        $consumption->opsBulkNoonReportConsumptionHeads()->createMany($consumptionHeadData);
                    }
                }
            }

            if(isset($request->opsBulkNoonReportDistance)){
                $bulk_noon_report->opsBulkNoonReportDistance()->create($request->opsBulkNoonReportDistance);
            }

            if(isset($request->opsBulkNoonReportEngineInputs)){
                $bulk_noon_report->opsBulkNoonReportEngineInputs()->createMany($request->opsBulkNoonReportEngineInputs);
            }

            // if(isset($request->opsBulkNoonReportEngineInputTypes)){
            //     $bulk_noon_report->opsBulkNoonReportEngineInputTypes()->createMany($request->opsBulkNoonReportEngineInputTypes);
            // }

            if(isset($request->opsBunkers)){
                $bulk_noon_report->opsBunkers()->createMany($request->opsBunkers);
            }


            $bulk_noon_report->opsBulkNoonReportDistance()->delete();
            $bulk_noon_report->opsBulkNoonReportDistance()->create($request->opsBulkNoonReportDistance);

            $bulk_noon_report->opsBulkNoonReportEngineInputs()->delete();
            $bulk_noon_report->opsBulkNoonReportEngineInputs()->createMany($request->opsBulkNoonReportEngineInputs);
            // $bulk_noon_report->opsBulkNoonReportEngineInputTypes()->delete();
            // $bulk_noon_report->opsBulkNoonReportEngineInputTypes()->createMany($request->opsBulkNoonReportEngineInputTypes);

            $bulk_noon_report->opsBunkers()->delete();
            $bulk_noon_report->opsBunkers()->createMany($request->opsBunkers);
            DB::commit();
            return response()->success('Bulk noon report updated successfully.', $bulk_noon_report, 202);
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
            DB::beginTransaction();
            $bulk_noon_report->opsBulkNoonReportPorts()->delete();
            $bulk_noon_report->opsBulkNoonReportCargoTanks()->delete();
            $bulk_noon_report->opsBulkNoonReportConsumptions()->delete();
            $bulk_noon_report->opsBulkNoonReportDistance()->delete();
            $bulk_noon_report->opsBulkNoonReportEngineInputs()->delete();
            // $bulk_noon_report->opsBulkNoonReportEngineInputTypes()->delete();
            $bulk_noon_report->opsBunkers()->delete();
            $bulk_noon_report->delete();
            DB::commit();

            return response()->json([
                'message' => 'Successfully deleted bulk noon report.',
            ], 204);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->json($bulk_noon_report->preventDeletionIfRelated(), 422);
        }
    }

    public function getBulkNoonReportByType(Request $request){
        try {
            $bulk_noon_reports = OpsBulkNoonReport::query()
            ->when(isset(request()->type), function($query){
                $query->where('type', 'like', '%' . request()->type . '%');                
            })
            ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($query){
                $query->where('business_unit', request()->business_unit);
            })
            ->limit(10)
            ->get();

            return response()->success('Successfully retrieved cargo tariffs name.', $bulk_noon_reports, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function exportBulkNoonReport(Request $request){
            $bulk_noon_report = OpsBulkNoonReport::query()
            ->when(isset(request()->type), function($query){
                $query->where('type', 'like', '%' . request()->type . '%');                
            })
            ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($query){
                $query->where('business_unit', request()->business_unit);
            })
            ->first();

            return Excel::download(new BulkNoonReportExport($bulk_noon_report), 'vessel_particular_report.xlsx');
    }


}
