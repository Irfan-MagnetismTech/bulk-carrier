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
use Modules\Operations\Entities\OpsChartererContract;
use Modules\Operations\Http\Requests\OpsChartererContractRequest;

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
    public function index(Request $request): JsonResponse
    {
        try {
            $charterer_contracts = OpsChartererContract::with('opsVessel',
            'opsChartererProfile','opsChartererContractsFinancialTerms.opsCargoTariff',
            'opsChartererContractsLocalAgents.opsPort')
            ->globalSearch($request->all());
            
            return response()->success('Data retrieved successfully.', $charterer_contracts, 200);
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
        try {
            DB::beginTransaction();
            $charterer_contract_info = $request->except(
                '_token',
                'attachment',
                'opsChartererContractsFinancialTerms',
                'opsChartererContractsLocalAgents'
            );

            if($request->file('attachment')){
                $attachment = $this->fileUpload->handleFile($request->attachment, 'ops/charterer_contracts');
                $charterer_contract_info['attachment'] = $attachment;
            }

            $charterer_contract = OpsChartererContract::create($charterer_contract_info);
     
            $charterer_contract->opsChartererContractsFinancialTerms()->create($request->opsChartererContractsFinancialTerms);
            $charterer_contract->opsChartererContractsLocalAgents()->createMany($request->opsChartererContractsLocalAgents);
            DB::commit();
            return response()->success('Data added successfully.', $charterer_contract, 201);
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
        $charterer_contract->load('opsVessel','opsChartererProfile','opsChartererContractsFinancialTerms.opsCargoTariff',
        'opsChartererContractsLocalAgents.opsPort');
        try
        {
            return response()->success('Data retrieved successfully.', $charterer_contract, 200);
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
                'opsChartererContractsFinancialTerms',
                'opsChartererContractsLocalAgents'
            );

            if($request->file('attachment')){
                $this->fileUpload->deleteFile($charterer_contract->attachment);
                $attachment = $this->fileUpload->handleFile($request->attachment, 'ops/charterer_contracts', $charterer_contract->attachment);
                $charterer_contract_info['attachment'] = $attachment;
            }
            
            $charterer_contract->update($charterer_contract_info);
            $charterer_contract->opsChartererContractsFinancialTerms()->delete();
            $charterer_contract->opsChartererContractsFinancialTerms()->create($request->opsChartererContractsFinancialTerms);
            $charterer_contract->opsChartererContractsLocalAgents()->createUpdateOrDelete($request->opsChartererContractsLocalAgents);
            DB::commit();
            return response()->success('Data updated successfully.', $charterer_contract, 202);
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
            $charterer_contract->opsChartererContractsFinancialTerms()->delete();
            $charterer_contract->opsChartererContractsLocalAgents()->delete();
            $this->fileUpload->deleteFile($charterer_contract->attachment);
            $charterer_contract->delete();

            return response()->json([
                'message' => 'Data deleted successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getChartererContractWithoutPaginate(){
        try {
            $charterer_contracts = OpsChartererContract::with('opsVessel','opsChartererProfile','opsChartererContractsFinancialTerms.opsCargoTariff',
            'opsChartererContractsLocalAgents.opsPort')->latest()->get();
            
            return response()->success('Data retrieved successfully.', $charterer_contracts, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getChartererContractByProfile(Request $request){
        try {
            $charterer = OpsChartererContract::query()->with('opsVessel','opsChartererProfile','opsChartererContractsFinancialTerms.opsCargoTariff.opsCargoType','opsChartererContractsFinancialTerms.opsVoyage',
            'opsChartererContractsLocalAgents.opsPort')
            ->when(isset(request()->ops_charterer_profile_id), function ($query) {
                    $query->where('ops_charterer_profile_id',request()->charterer_profile_id);
            })
            ->get();            
            // $charterer->load('opsChartererProfile');

            return response()->success('Data retrieved successfully.', $charterer, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function downloadContractAttachment(Request $request)
    {
        $particular= OpsChartererContract::find($request->id);
        $filePath=null;
        $fileName=null;
        if(isset($particular->attachment)){  
            $filePath= public_path($particular->attachment);
            $fileName = basename($filePath);
        }
        if (file_exists($filePath)) {
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment',
            ]);
        } else {
            return response()->error(['message' => 'File not found.'], 404);
        }
        
    }

}
