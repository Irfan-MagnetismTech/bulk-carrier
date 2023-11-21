<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsBunkerRequisition;
use Modules\Operations\Http\Requests\OpsBunkerRequisitionRequest;
use Illuminate\Support\Facades\DB;

class OpsBunkerRequisitionController extends Controller
{
   // use HasRoles;
   
//    function __construct(private FileUploadService $fileUpload)
//    {
   //     $this->middleware('permission:bunker-requisition-create|bunker-requisition-edit|bunker-requisition-show|bunker-requisition-delete', ['only' => ['index','show']]);
   //     $this->middleware('permission:bunker-requisition-create', ['only' => ['store']]);
   //     $this->middleware('permission:bunker-requisition-edit', ['only' => ['update']]);
   //     $this->middleware('permission:bunker-requisition-delete', ['only' => ['destroy']]);
//    }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
    public function index(Request $request): JsonResponse
    {
        try {
            $bunker_requisitions = OpsBunkerRequisition::with('opsVessel','opsVoyage','opsBunkers')->latest()->paginate(15);
            
            return response()->success('Successfully retrieved bunker requisitions.', $bunker_requisitions, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
 
 
        /**
      * Store a newly created resource in storage.
      * 
      * @param OpsBunkerRequisitionRequest $request
      * @return JsonResponse
     */
     public function store(OpsBunkerRequisitionRequest $request): JsonResponse
     {
         // dd($request);
         try {
             DB::beginTransaction();
             $bunkerRequisitionInfo = $request->except(
                 '_token',
                 'opsBunkers',
             );
 
             $bunker_requisition = OpsBunkerRequisition::create($bunkerRequisitionInfo);            
             $bunker_requisition->opsBunkers()->createMany($request->opsBunkers);
             DB::commit();
             return response()->success('Bunker requisition added successfully.', $bunker_requisition, 201);
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
      * @param  OpsBunkerRequisition  $bunker_requisition
      * @return JsonResponse
      */
     public function show(OpsBunkerRequisition $bunker_requisition): JsonResponse
     {
         $bunker_requisition->load('opsVessel','opsVoyage','opsBunkers');
         try
         {
             return response()->success('Successfully retrieved bunker requisition.', $bunker_requisition, 200);
         }
         catch (QueryException $e)
         {
             return response()->error($e->getMessage(), 500);
         }
 
     }
 
 
       /**
      * Update the specified resource in storage.
      *
      * @param OpsBunkerRequisitionRequest $request
      * @param  OpsBunkerRequisition  $bunker_requisition
      * @return JsonResponse
      */
     public function update(OpsBunkerRequisitionRequest $request, OpsBunkerRequisition $bunker_requisition): JsonResponse
     {
         try {
             DB::beginTransaction();
             $bunkerRequisitionInfo = $request->except(
                 '_token',
                 'opsBunkers',
             );
             
             $bunker_requisition->update($bunkerRequisitionInfo);            
             $bunker_requisition->opsBunkers()->createUpdateOrDelete($request->opsBunkers);
             DB::commit();
             return response()->success('Bunker requisition updated successfully.', $bunker_requisition, 200);
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
      * @param  OpsBunkerRequisition  $bunker_requisition
      * @return \Illuminate\Http\JsonResponse
      */
     public function destroy(OpsBunkerRequisition $bunker_requisition): JsonResponse
     {
         try
         {
             $bunker_requisition->opsBunkers()->delete();
             $bunker_requisition->delete();
 
             return response()->json([
                 'message' => 'Successfully deleted bunker requisition.',
             ], 204);
         }
         catch (QueryException $e)
         {
             return response()->error($e->getMessage(), 500);
         }
     }
 
    //  public function getBunkerRequisitionByName(Request $request){
    //      try {
    //          $bunker_requisitions = OpsBunkerRequisition::query()
    //          ->where(function ($query) use($request) {
    //              $query->where('tariff_name', 'like', '%' . $request->tariff_name . '%');                
    //          })
    //          ->when(request()->business_unit != "ALL", function($q){
    //              $q->where('business_unit', request()->business_unit);  
    //          })
    //          ->limit(10)
    //          ->get();
 
    //          return response()->success('Successfully retrieved cargo tariffs name.', $bunker_requisitions, 200);
    //      } catch (QueryException $e){
    //          return response()->error($e->getMessage(), 500);
    //      }
    //  }
}
