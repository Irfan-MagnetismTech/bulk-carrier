<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
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
        $voyage->load('opsCustomer','opsVessel','opsCargoType','opsVoyageSectors.loadingPoint','opsVoyageSectors.unloadingPoint','opsVoyagePortSchedules.portCode','opsBunkers');

        $voyage->opsVoyageSectors->map(function($sector) {
            $sector->voyage_sector_id = $sector->id;
            $sector->loading_point_name_code = $sector->loadingPoint->name.'-'.$sector->loadingPoint->code;
            $sector->unloading_point_name_code = $sector->unloadingPoint->name.'-'.$sector->unloadingPoint->code;
            return $sector;
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
            ->where(function ($query) use($request) {
                $query->where('voyage_no', 'like', '%' . $request->voyage_no . '%');                
            })
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })
            ->when(request()->vessel_id != 'null', function($q) {
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
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })
            ->when(request()->vessel_id != 'null', function($q) {
                $q->where('ops_vessel_id', request()->ops_vessel_id);
            })
            ->get();

            return response()->success('Data retrieved successfully.', $voyages, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }


}
