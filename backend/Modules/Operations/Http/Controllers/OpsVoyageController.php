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
            $voyages = OpsVoyage::with('opsCustomer','opsVessel','opsMotherVessel','opsCargoType','opsVoyageSectors','opsVoyagePortSchedules','opsBunkers')->latest()->paginate(15);
            
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
                'opsVoyageSector',
                'opsVoyagePortSchedule',
                'opsBunker',
            );

            $voyage = OpsCargoTariff::create($voyageInfo);
            $voyage->opsVoyageSectors()->createMany($request->opsVoyageSector);
            $voyage->opsVoyagePortSchedules()->createMany($request->opsVoyagePortSchedule);
            $voyage->opsBunkers()->createMany($request->opsBunker);
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
        $voyage->load('opsCustomer','opsVessel','opsMotherVessel','opsCargoType','opsVoyageSectors','opsVoyagePortSchedules','opsBunkers');
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
                'opsVoyageSector',
                'opsVoyagePortSchedule',
                'opsBunker',
            );

            $voyageUpdate->update($voyageInfo);  

            $voyageUpdate->opsVoyageSectors()->delete();
            $voyageUpdate->opsVoyageSectors()->createMany($request->opsVoyageSector);

            $voyageUpdate->opsVoyagePortSchedules()->delete();
            $voyageUpdate->opsVoyagePortSchedules()->createMany($request->opsVoyagePortSchedule);

            $voyageUpdate->opsBunkers()->delete();
            $voyageUpdate->opsBunkers()->createMany($request->opsBunker);

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
            $voyage->opsVoyageSectors()->delete();
            $voyage->opsVoyagePortSchedules()->delete();
            $voyage->opsBunkers()->delete();
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
            $voyages = OpsVoyage::with('opsCustomer','opsVessel','opsMotherVessel','opsCargoType','opsVoyageSectors','opsVoyagePortSchedules','opsBunkers')->latest()->get();        
            return response()->success('Successfully retrieved voyages for without paginate.', $voyages, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
