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
use Modules\Operations\Entities\OpsBunkerBill;
use Modules\Operations\Http\Requests\OpsBunkerBillRequest;

class OpsBunkerBillController extends Controller
{
   // use HasRoles;

   function __construct(private FileUploadService $fileUpload)
   {
   //     $this->middleware('permission:bunker-bill-create|bunker-bill-edit|bunker-bill-show|bunker-bill-delete', ['only' => ['index','show']]);
   //     $this->middleware('permission:bunker-bill-create', ['only' => ['store']]);
   //     $this->middleware('permission:bunker-bill-edit', ['only' => ['update']]);
   //     $this->middleware('permission:bunker-bill-delete', ['only' => ['destroy']]);
   }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
    public function index(Request $request): JsonResponse
    {
        try {
            $bunker_bills = OpsBunkerBill::with('scmVendor','opsBunkerBillLines')
            ->globalSearch($request->all());
            
            return response()->success('Data retrieved successfully.', $bunker_bills, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
 
 
    /**
     * Store a newly created resource in storage.
    * 
    * @param OpsBunkerBillRequest $request
    * @return JsonResponse
    */
    public function store(OpsBunkerBillRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $bunker_bill_info = $request->except(
                '_token',
                'attachment',
                'smr_file_path',
                'opsBunkerBillLines',
            );

            if(isset($request->attachment)){
                $attachment = $this->fileUpload->handleFile($request->attachment, 'ops/bunker_bills');
                $bunker_bill_info['attachment'] = $attachment;
            }

            if(isset($request->smr_file_path)){                
                $smr_file_path = $this->fileUpload->handleFile($request->attachment, 'ops/bunker_bills/srm_file');
                $bunker_bill_info['smr_file_path'] = $smr_file_path;
            }

            $bunker_bill = OpsBunkerBill::create($bunker_bill_info);
            $bunker_bill->opsBunkerBillLines()->createMany($request->opsBunkerBillLines);
            DB::commit();
            return response()->success('Data added successfully.', $bunker_bill, 201);
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
    * @param  OpsBunkerBill  $bunker_bill
    * @return JsonResponse
    */
    public function show(OpsBunkerBill $bunker_bill): JsonResponse
    {
        $bunker_bill->load('scmVendor','opsBunkerBillLines');
        try
        {
            return response()->success('Data retrieved successfully.', $bunker_bill, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }
 
 
    /**
     * Update the specified resource in storage.
    *
    * @param OpsBunkerBillRequest $request
    * @param  OpsBunkerBill  $bunker_bill
    * @return JsonResponse
    */
    public function update(OpsBunkerBillRequest $request, OpsBunkerBill $bunker_bill): JsonResponse
    {
        try {
            DB::beginTransaction();
            $bunker_bill_info = $request->except(
                '_token',
                'attachment',
                'smr_file_path',
                'opsBunkerBillLines',
            );
                       
            if(isset($request->attachment)){
                $this->fileUpload->deleteFile($bunker_bill->attachment);
                $attachment = $this->fileUpload->handleFile($request->attachment, 'ops/bunker_bills', $bunker_bill->attachment);
                $bunker_bill_info['attachment'] = $attachment;
            }

            if(isset($request->smr_file_path)){
                $this->fileUpload->deleteFile($bunker_bill->smr_file_path);
                $smr_file_path = $this->fileUpload->handleFile($request->smr_file_path, 'ops/bunker_bills', $bunker_bill->smr_file_path);
                $bunker_bill_info['smr_file_path'] = $smr_file_path;
            }

            $bunker_bill->update($bunker_bill_info);            
            $bunker_bill->opsBunkerBillLines()->createUpdateOrDelete($request->opsBunkerBillLines);
            DB::commit();
            return response()->success('Data retrieved successfully.', $bunker_bill, 202);
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
    * @param  OpsBunkerBill  $bunker_bill
    * @return \Illuminate\Http\JsonResponse
    */
    public function destroy(OpsBunkerBill $bunker_bill): JsonResponse
    {
        try
        {
            if(isset($bunker_bill->attachment)){                
                $this->fileUpload->deleteFile($bunker_bill->attachment);
            }
            if(isset($bunker_bill->smr_file_path)){  
                $this->fileUpload->deleteFile($bunker_bill->smr_file_path);
            }
            $bunker_bill->opsBunkerBillLines()->delete();
            $bunker_bill->delete();

            return response()->json([
                'message' => 'Data deleted successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getBunkerBillByName(Request $request){
        try {
            $bunker_bills = OpsBunkerBill::query()->with('scmVendor','opsBunkerBillLines')
            ->where(function ($query) use($request) {
                $query->where('vendor_bill_no', 'like', '%' . $request->vendor_bill_no . '%');                
            })
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->limit(10)
            ->get();

            return response()->success('Data retrieved successfully.', $bunker_bills, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }
}
