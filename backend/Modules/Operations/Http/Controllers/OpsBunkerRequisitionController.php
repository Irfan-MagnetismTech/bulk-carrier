<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsBunkerRequisition;
use Modules\Operations\Http\Requests\OpsBunkerRequisitionRequest;

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
            $bunker_requisitions = OpsBunkerRequisition::with('opsVessel','opsVoyage','opsBunkers')
            ->globalSearch($request->all());
            
            return response()->success('Data retrieved successfully.', $bunker_requisitions, 200);
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
         dd($request);
         try {
             DB::beginTransaction();
             $bunkerRequisitionInfo = $request->except(
                 '_token',
                 'opsBunkers',
             );
 
             $bunker_requisition = OpsBunkerRequisition::create($bunkerRequisitionInfo);            
             $bunker_requisition->opsBunkers()->createMany($request->opsBunkers);
             DB::commit();
             return response()->success('Data added successfully.', $bunker_requisition, 201);
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
             return response()->success('Data retrieved successfully.', $bunker_requisition, 200);
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
             $bunker_requisition->opsBunkers()->delete();
             $bunker_requisition->opsBunkers()->createMany($request->opsBunkers);
             DB::commit();
             return response()->success('Data updated successfully.', $bunker_requisition, 202);
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
                 'message' => 'Data deleted successfully.',
             ], 204);
         }
         catch (QueryException $e)
         {
             return response()->error($e->getMessage(), 500);
         }
     }
 
     public function getBunkerRequisitionByReqNo(Request $request){
         try {
             $bunker_requisitions = OpsBunkerRequisition::query()
            ->when(isset(request()->requisition_no), function($query){
                 $query->where('requisition_no', 'like', '%' . request()->requisition_no . '%');                
             })
             ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($query){
                 $query->where('business_unit', request()->business_unit);  
             })
             ->get();
 
             return response()->success('Data retrieved successfully.', $bunker_requisitions, 200);
         } catch (QueryException $e){
             return response()->error($e->getMessage(), 500);
         }
     }
}
