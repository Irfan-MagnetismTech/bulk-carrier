<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsCustomerInvoice;
use Modules\Operations\Http\Requests\OpsCustomerInvoiceRequest;
use Illuminate\Support\Facades\DB;

class OpsCustomerInvoiceController extends Controller
{
   // use HasRoles;
   
//    function __construct(private FileUploadService $fileUpload)
//    {
   //     $this->middleware('permission:customer-invoice-create|customer-invoice-edit|customer-invoice-show|customer-invoice-delete', ['only' => ['index','show']]);
   //     $this->middleware('permission:customer-invoice-create', ['only' => ['store']]);
   //     $this->middleware('permission:customer-invoice-edit', ['only' => ['update']]);
   //     $this->middleware('permission:customer-invoice-delete', ['only' => ['destroy']]);
//    }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
    public function index(Request $request): JsonResponse
    {
        try {
            $customerInvoices = OpsCustomerInvoice::with('opsCustomer','opsCustomerInvoiceLines')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->latest()
            ->paginate(10);
            
            return response()->success('Successfully retrieved customer invoices.', $customerInvoices, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
 
 
        /**
      * Store a newly created resource in storage.
      * 
      * @param OpsCustomerInvoiceRequest $request
      * @return JsonResponse
     */
     public function store(OpsCustomerInvoiceRequest $request): JsonResponse
     {
         // dd($request);
         try {
             DB::beginTransaction();
             $customerInvoiceInfo = $request->except(
                 '_token',
                 'opsCustomerInvoiceLines',
             );
 
             $customerInvoice = OpsCustomerInvoice::create($customerInvoiceInfo);
             $customerInvoice->opsCustomerInvoiceLines()->createMany($request->opsCustomerInvoiceLines);
             DB::commit();
             return response()->success('Customer invoice added successfully.', $customerInvoice, 201);
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
      * @param  OpsCustomerInvoice  $customer_invoice
      * @return JsonResponse
      */
     public function show(OpsCustomerInvoice $customer_invoice): JsonResponse
     {
         $customer_invoice->load('opsCustomer','opsCustomerInvoiceLines');
         try
         {
            return response()->success('Successfully retrieved customer invoice.', $customer_invoice, 200);
         }
         catch (QueryException $e)
         {
             return response()->error($e->getMessage(), 500);
         }
 
     }
 
 
       /**
      * Update the specified resource in storage.
      *
      * @param OpsCustomerInvoiceRequest $request
      * @param  OpsCustomerInvoice  $customer_invoice
      * @return JsonResponse
      */
     public function update(OpsCustomerInvoiceRequest $request, OpsCustomerInvoice $customer_invoice): JsonResponse
     {
         try {
            DB::beginTransaction();            
            $customerInvoiceInfo = $request->except(
                '_token',
                'opsCustomerInvoiceLines',
            );
        
            $customer_invoice->update($customerInvoiceInfo);        
            $customer_invoice->opsCustomerInvoiceLines()->createUpdateOrDelete($request->opsCustomerInvoiceLines);
            DB::commit();
            return response()->success('Customer invoice updated successfully.', $customer_invoice, 200);
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
      * @param  OpsCustomerInvoice  $customer_invoice
      * @return \Illuminate\Http\JsonResponse
      */
     public function destroy(OpsCustomerInvoice $customer_invoice): JsonResponse
     {
        try
        {
            $customer_invoice->opsCustomerInvoiceLines()->delete();
            $customer_invoice->delete();

            return response()->json([
                'message' => 'Successfully deleted customer invoice.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
     }
}
