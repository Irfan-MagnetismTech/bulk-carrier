<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Modules\Operations\Entities\OpsVoyage;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Http\Requests\OpsVoyageRequest;

class OpsVoyageController extends Controller
{
//    use HasRoles;
   
//    function __construct(private FileUploadService $fileUpload)
//    {
//        $this->middleware('permission:voyage-create|voyage-edit|voyage-show|voyage-delete', ['only' => ['index','show']]);
//        $this->middleware('permission:voyage-create', ['only' => ['store']]);
//        $this->middleware('permission:voyage-edit', ['only' => ['update']]);
//        $this->middleware('permission:voyage-delete', ['only' => ['destroy']]);
//    }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    **/
    public function index(Request $request) : JsonResponse
    {
        try {
            $voyages = OpsVoyage::with('opsCustomer','opsVessel','opsCargoType','opsVoyageSectors','opsVoyagePortSchedules','opsBunkers')
            ->globalSearch($request->all());
            
            return response()->success('Data retrieved successfully.', $voyages, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
 
 
        /**
      * Store a newly created resource in storage.
      * 
      * @param OpsVoyageRequest $request
      * @return JsonResponse
     */
     public function store(OpsVoyageRequest $request): JsonResponse
     {
        // dd($request);
        try {
            DB::beginTransaction();
            $voyageInfo = $request->except(
                '_token',
                'opsVoyageSectors',
                'opsVoyagePortSchedules',
                'opsBunkers',
            );

            foreach($request->opsVoyageSectors as $key=>$sector){
                if($sector['loading_point'] == $sector['unloading_point']){
                    $error= [
                        'message'=>'In Sectors - Loading Point and Unloading Point can not be same for the row '.$key++.'.',
                        'errors'=>[
                            'unloading_point'=>['In Sectors - Loading Point and Unloading Point can not be same for the row '.$key++.'.',]
                            ]
                        ];
                    return response()->json($error, 422);
                }
            }

            $schedules= [];
            foreach($request->opsVoyagePortSchedules as $key=>$schedule){
                $schedules[]=$schedule['port_code'];
            }
            
            if (count($schedules) !== count(array_unique($schedules))) {
                $error= [
                    'message'=>'In Schedules - Port can not be same.',
                    'errors'=>[
                        'unloading_point'=>['In Schedules - Port can not be same.',]
                        ]
                    ];
                return response()->json($error, 422);
            }

            $voyage = OpsVoyage::create($voyageInfo);
            $voyage->opsVoyageSectors()->createMany($request->opsVoyageSectors);
            $voyage->opsVoyagePortSchedules()->createMany($request->opsVoyagePortSchedules);
            $voyage->opsBunkers()->createMany($request->opsBunkers);
            DB::commit();
            return response()->success('Data added successfully.', $voyage, 201);
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
      * @param  OpsVoyage  $voyage
      * @return JsonResponse
      */
     public function show(OpsVoyage $voyage): JsonResponse
     {
        $voyage->load('opsCustomer','opsVessel','opsCargoType','opsVoyageSectors.loadingPoint','opsVoyageSectors.unloadingPoint','opsVoyagePortSchedules.portCode','opsBunkers.scmMaterial');

        $voyage->opsVoyageSectors->map(function($sector) {
            $sector->voyage_sector_id = $sector->id;
            $sector->loading_point_name_code = $sector->loadingPoint->name.'-'.$sector->loadingPoint->code;
            $sector->unloading_point_name_code = $sector->unloadingPoint->name.'-'.$sector->unloadingPoint->code;
            return $sector;
        });
        
        $voyage->opsBunkers->map(function($bunker) {
            $bunker->id = $bunker->scmMaterial->id;
            $bunker->name = $bunker->scmMaterial->name;
            $bunker->is_readonly = true;
            return $bunker;
        });

        try
        {
            return response()->success('Data retrieved successfully.', $voyage, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
 
     }
 

       /**
      * Update the specified resource in storage.
      *
      * @param OpsVoyageRequest $request
      * @param  OpsVoyageRequest  $voyage
      * @return JsonResponse
      */
     public function update(OpsVoyageRequest $request, OpsVoyage $voyage): JsonResponse
     {
        try {
            DB::beginTransaction();
            $voyageInfo = $request->except(
                '_token',
                'opsVoyageSectors',
                'opsVoyagePortSchedules',
                'opsBunkers',
            );

            foreach($request->opsVoyageSectors as $key=>$sector){
                if($sector['loading_point'] == $sector['unloading_point']){
                    $error= [
                        'message'=>'In Sectors - Loading Point and Unloading Point can not be same for the row '.$key++.'.',
                        'errors'=>[
                            'unloading_point'=>['In Sectors - Loading Point and Unloading Point can not be same for the row '.$key++.'.',]
                            ]
                        ];
                    return response()->json($error, 422);
                }
            }

            $schedules= [];
            foreach($request->opsVoyagePortSchedules as $key=>$schedule){
                $schedules[]=$schedule['port_code'];
            }
            
            if (count($schedules) !== count(array_unique($schedules))) {
                $error= [
                    'message'=>'In Schedules - Port can not be same.',
                    'errors'=>[
                        'unloading_point'=>['In Schedules - Port can not be same.',]
                        ]
                    ];
                return response()->json($error, 422);
            }

            $voyage->update($voyageInfo);  

            $voyage->opsVoyageSectors()->delete();
            $voyage->opsVoyageSectors()->createMany($request->opsVoyageSectors);

            $voyage->opsVoyagePortSchedules()->delete();
            $voyage->opsVoyagePortSchedules()->createMany($request->opsVoyagePortSchedules);

            $voyage->opsBunkers()->delete();
            $voyage->opsBunkers()->createMany($request->opsBunkers);

            DB::commit();
            return response()->success('Data updated Successfully.', $voyage, 202);
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
      * @param  OpsVoyage  $voyage
      * @return \Illuminate\Http\JsonResponse
      */
    public function destroy(OpsVoyage $voyage): JsonResponse
    {
        try
        {
            $voyage->opsVoyageSectors()->delete();
            $voyage->opsVoyagePortSchedules()->delete();
            $voyage->opsBunkers()->delete();
            $voyage->delete();

            return response()->json([
                'message' => 'Data deleted Successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchVoyages(Request $request){
        try {
            $voyages = OpsVoyage::query()
            ->when(isset(request()->voyage_no), function($query) {
                $query->where('voyage_no', 'like', '%' . request()->voyage_no . '%');                
            })
            ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })
            ->when(isset(request()->vessel_id), function($q) {
                $q->where('ops_vessel_id', request()->vessel_id);
            })
            ->limit(10)
            ->get();

            return response()->success('Data retrieved successfully.', $voyages, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getSearchVoyages(Request $request){

        try {
            $voyages = OpsVoyage::query()
            ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($query){
                $query->where('business_unit', request()->business_unit);
            })
            ->when(isset(request()->ops_vessel_id) && (request()->ops_vessel_id != 'null'), function($query) {
                $query->where('ops_vessel_id', request()->ops_vessel_id);
            })
            ->with('opsVoyageSectors')
            ->get();

            $voyages = $voyages->map(function($voyage) {
                $voyage->opsVoyageSectors->map(function($sector) {
                    $sector['quantity'] = $this->chooseQuantity($sector);

                    return $sector;
                });

                return $voyage;
            });

            return response()->success('Data retrieved successfully.', $voyages, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function chooseQuantity($item) {
        $cargo_quantity = 0;
            if ($item->final_received_qty > 0) {
                $cargo_quantity = $item->final_received_qty;
            } elseif ($item->final_survey_qty > 0) {
                $cargo_quantity = $item->final_survey_qty;
            } elseif ($item->boat_note_qty > 0) {
                $cargo_quantity = $item->boat_note_qty;
            } elseif ($item->initial_survey_qty > 0) {
                $cargo_quantity = $item->initial_survey_qty;
            }

            return $cargo_quantity;
    }


}
