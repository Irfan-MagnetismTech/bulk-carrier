<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsChartererContract;
use Modules\Operations\Http\Requests\OpsChartererContractRequest;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\DB;

class OpsChartererContractController extends Controller
{
   // use HasRoles;
   
   function __construct(private FileUploadService $fileUpload)
   {
   //     $this->middleware('permission:charterer-contract-create|charterer-contract-edit|charterer-contract-show|charterer-contract-delete', ['only' => ['index','show']]);
   //     $this->middleware('permission:charterer-contract-create', ['only' => ['store']]);
   //     $this->middleware('permission:charterer-contract-edit', ['only' => ['update']]);
   //     $this->middleware('permission:charterer-contract-delete', ['only' => ['destroy']]);
   }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
   public function index()
   {
       try {
           $charterer_contracts = OpsChartererContract::with('opsVessel','opsChartererProfile')->latest()->paginate(15);
           
           return response()->success('Successfully retrieved charterer contract.', $charterer_contracts, 200);
       }
       catch (QueryException $e)
       {
           return response()->error($e->getMessage(), 500);
       }
   }


      /**
    * Store a newly created resource in storage.
    * 
    * @param OpsChartererContractRequest $request
    * @return JsonResponse
   */
   public function store(OpsChartererContractRequest $request): JsonResponse
   {
       // dd($request);
       try {
           DB::beginTransaction();
           $charterer_contract = $request->except(
               '_token',
               'attachment',
           );
           if(isset($request->attachment)){
               $attachment = $this->fileUpload->handleFile($request->attachment, 'ops/charterer_contracts');
               $charterer_contract['attachment'] = $attachment;
           }
           $charterer_contract = OpsChartererContract::create($charterer_contract);
           DB::commit();
           return response()->success('Charterer contract added successfully.', $charterer_contract, 201);
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
    * @param  OpsChartererContract  $charterer_contract
    * @return JsonResponse
    */
   public function show(OpsChartererContract $charterer_contract): JsonResponse
   {
       $charterer_contract->load('opsVessel','opsChartererProfile');
       try
       {
           return response()->success('Successfully retrieved charterer contract.', $charterer_contract, 200);
       }
       catch (QueryException $e)
       {
           return response()->error($e->getMessage(), 500);
       }

   }

     /**
    * Update the specified resource in storage.
    *
    * @param OpsChartererContractRequest $request
    * @param  OpsChartererContract  $charterer_contract
    * @return JsonResponse
    */
   public function update(OpsChartererContractRequest $request, OpsChartererContract $charterer_contract): JsonResponse
   {
       try {
           DB::beginTransaction();
           $charterer_contract_info = $request->except(
               '_token',
               'attachment',
           );

           if(isset($request->attachment)){
               $this->fileUpload->deleteFile($charterer_contract->attachment);
               $attachment = $this->fileUpload->handleFile($request->attachment, 'ops/charterer_contracts', $charterer_contract->attachment);
               $charterer_contract_info['attachment'] = $attachment;
           }
           
           $charterer_contract->update($charterer_contract_info);
           DB::commit();
           return response()->success('Charterer contract updated successfully.', $charterer_contract, 200);
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
    * @param  OpsChartererContract  $charterer_contract
    * @return \Illuminate\Http\JsonResponse
    */
   public function destroy(OpsChartererContract $charterer_contract): JsonResponse
   {
       try
       {
           $this->fileUpload->deleteFile($charterer_contract->attachment);
           $charterer_contract->delete();

           return response()->json([
               'message' => 'Successfully deleted charterer contract.',
           ], 204);
       }
       catch (QueryException $e)
       {
           return response()->error($e->getMessage(), 500);
       }
   }

   public function getChartererContractWithoutPaginate(){
       try {
           $charterer_contracts = OpsChartererContract::with('opsVessel','opsChartererProfile')->latest()->get();
           return response()->success('Successfully retrieved charterer contracts contract type.', collect($charterer_contracts->pluck('contract_type'))->unique()->values()->all(), 200);
       } catch (QueryException $e){
           return response()->error($e->getMessage(), 500);
       }
   }

}
