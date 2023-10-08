<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsVoyage;
use Modules\Operations\Http\Requests\OpsVoyageRequest;
use Illuminate\Support\Facades\DB;

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
    public function index()
    {
        // dd('voyage');
        try {
            $voyages = OpsVoyage::with('ops_customer','ops_vessel','ops_mother_vessel','ops_cargo_type','ops_voyage_sectors','ops_voyage_port_schedules','ops_bunkers')->latest()->paginate(15);
            
            return response()->success('Successfully retrieved voyage.', $voyages, 200);
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
        try {
            DB::beginTransaction();
            $voyageInfo = $request->except(
                '_token',
                'ops_voyage_sectors',
                'ops_voyage_port_schedules',
                'ops_bunkers',
            );

            $voyage = OpsCargoTariff::create($voyageInfo);
            $voyage->ops_voyage_sectors()->createMany($request->ops_voyage_sectors);
            $voyage->ops_voyage_port_schedules()->createMany($request->ops_voyage_port_schedules);
            $voyage->ops_bunkers()->createMany($request->ops_bunkers);
            DB::commit();
            return response()->success('Voyage added successfully.', $voyage, 201);
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
      * @param  OpsCargoTariff  $cargo_tariff
      * @return JsonResponse
      */
     public function show(OpsVoyageRequest $voyage): JsonResponse
     {
        $voyage->load('ops_customer','ops_vessel','ops_mother_vessel','ops_cargo_type','ops_voyage_sectors','ops_voyage_port_schedules','ops_bunkers');
        try
        {
            return response()->success('Successfully retrieved voyage.', $voyage, 200);
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
                'ops_voyage_sectors',
                'ops_voyage_port_schedules',
                'ops_bunkers',
            );

            $voyageUpdate->update($voyageInfo);  

            $voyageUpdate->ops_voyage_sectors()->delete();
            $voyageUpdate->ops_voyage_sectors()->createMany($request->ops_voyage_sectors);

            $voyageUpdate->ops_voyage_port_schedules()->delete();
            $voyageUpdate->ops_voyage_port_schedules()->createMany($request->ops_voyage_port_schedule);

            $voyageUpdate->ops_bunkers()->delete();
            $voyageUpdate->ops_bunkers()->createMany($request->ops_bunkers);

            DB::commit();
            return response()->success('Voyage updated successfully.', $voyage, 200);
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
            $voyage->ops_voyage_sectors()->delete();
            $voyage->ops_voyage_port_schedules()->delete();
            $voyage->ops_bunkers()->delete();
            $voyage->delete();

            return response()->json([
                'message' => 'Successfully deleted voyage.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getVoyageName(){
        try {
            $voyages = OpsVoyage::all();
            return response()->success('Successfully retrieved voyages name.', collect($voyages->pluck('route'))->unique()->values()->all(), 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getVoyageWithoutPaginate(){
        try
        {
            $voyages = OpsVoyage::with('ops_customer','ops_vessel','ops_mother_vessel','ops_cargo_type','ops_voyage_sectors','ops_voyage_port_schedules','ops_bunkers')->latest()->get();        
            return response()->success('Successfully retrieved voyages for without paginate.', $voyages, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
