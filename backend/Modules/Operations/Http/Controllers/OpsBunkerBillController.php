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
            $bunker_bills = OpsBunkerBill::with('scmVendor','opsBunkerBillLines.opsBunkerBillLineItems')
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
    // public function store(Request $request): JsonResponse
    {
        dd($request->all());
        // $bunkerBillLines = $request->opsBunkerBillLines;

        // if(isset($request->opsBunkerBillLines)){
        //     $bunker_requisition_ids= [];
        //     foreach($request->opsBunkerBillLines as $key=>$billLine){
        //         $bunker_requisition_ids[]=$billLine['ops_bunker_requisition_id'];
        //     }    

        //     if (count($bunker_requisition_ids) !== count(array_unique($bunker_requisition_ids))) {
        //         $error= [
        //             'message'=>'PR No. can not be same.',
        //             'errors'=>[
        //                 'ops_bunker_requisition_id'=>['PR No. can not be same.',]
        //                 ]
        //             ];
        //         return response()->json($error, 422);
        //     }
        // }

        // return response()->json(count($bunkerBillLines));
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
                $smr_file_path = $this->fileUpload->handleFile($request->smr_file_path, 'ops/bunker_bills/srm_file');
                $bunker_bill_info['smr_file_path'] = $smr_file_path;
            }

            // return response()->json($bunker_bill_info);

            $bunker_bill = OpsBunkerBill::create($bunker_bill_info);

            if(isset($request->opsBunkerBillLines)){
                foreach ($request->opsBunkerBillLines as $lineData) {

                    $line = $bunker_bill->opsBunkerBillLines()->create($lineData);

                    // return response()->json($line);

                    if(isset($lineData['opsBunkerBillLineItems'])) {
                        
                        $lineItemData = collect($lineData['opsBunkerBillLineItems'])->map(function($item) use($bunker_bill) {
                            $item['ops_bunker_bill_id'] = $bunker_bill->id;
                            return $item;
                        })->toArray();

                        $line->opsBunkerBillLineItems()->createMany($lineItemData);
                    }
                }
            }

        // return response()->json(count($bunkerBillLines));

            
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
        $bunker_bill->load('scmVendor','opsBunkerBillLines.opsBunkerBillLineItems', 'opsBunkerBillLines.opsBunkerRequisition');

        $bunker_bill->opsBunkerBillLines->map(function($line) {
            $line['requisitionBunkers'] = $line->opsBunkerRequisition->opsBunkers->map(function($item) {
                $item['name'] = $item->scmMaterial->name;
                return $item;
            });

            return $line;
        });
        
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

        // if(isset($request->opsBunkerBillLines)){
        //     $bunker_requisition_ids= [];
        //     foreach($request->opsBunkerBillLines as $key=>$billLine){
        //         $bunker_requisition_ids[]=$billLine['ops_bunker_requisition_id'];
        //     }

        //     if (count($bunker_requisition_ids) !== count(array_unique($bunker_requisition_ids))) {
        //         $error= [
        //             'message'=>'PR No. can not be same.',
        //             'errors'=>[
        //                 'ops_bunker_requisition_id'=>['PR No. can not be same.',]
        //                 ]
        //             ];
        //         return response()->json($error, 422);
        //     }
        // }

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
            $bunker_bill->opsBunkerBillLineItems()->delete();
            $bunker_bill->opsBunkerBillLines()->delete();


            if(isset($request->opsBunkerBillLines)){
                foreach ($request->opsBunkerBillLines as $lineData) {

                    $line = $bunker_bill->opsBunkerBillLines()->create($lineData);

                    // return response()->json($line);

                    if(isset($lineData['opsBunkerBillLineItems'])) {
                        
                        $lineItemData = collect($lineData['opsBunkerBillLineItems'])->map(function($item) use($bunker_bill) {
                            $item['ops_bunker_bill_id'] = $bunker_bill->id;
                            return $item;
                        })->toArray();

                        $line->opsBunkerBillLineItems()->createMany($lineItemData);
                    }
                }
            }
            
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
            $bunker_bill->opsBunkerBillLineItems()->delete();
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
