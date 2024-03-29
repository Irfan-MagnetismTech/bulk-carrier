<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsChartererInvoice;
use Modules\Operations\Http\Requests\OpsChartererInvoiceRequest;
use Illuminate\Support\Facades\DB;

class OpsChartererInvoiceController extends Controller
{
   // use HasRoles;
  
//    function __construct()
//    {
   //     $this->middleware('permission:charterer-invoice-create|charterer-invoice-edit|charterer-invoice-show|charterer-invoice-delete', ['only' => ['index','show']]);
   //     $this->middleware('permission:charterer-invoice-create', ['only' => ['store']]);
   //     $this->middleware('permission:charterer-invoice-edit', ['only' => ['update']]);
   //     $this->middleware('permission:charterer-invoice-delete', ['only' => ['destroy']]);
//    }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
    public function index()
    {
        try {
            $charterer_invoices = OpsChartererInvoice::with('opsChartererProfile','opsChartererContract','opsChartererInvoiceLines')->latest()->paginate(15);
            
            return response()->success('Successfully retrieved charterer invoices.', $charterer_invoices, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
 
 
        /**
      * Store a newly created resource in storage.
      * 
      * @param OpsChartererInvoiceRequest $request
      * @return JsonResponse
     */
     public function store(OpsChartererInvoiceRequest $request): JsonResponse
     {
         try {
             DB::beginTransaction();
             $charterer_invoice_info = $request->except(
                 '_token',
                 'opsChartererInvoiceLines',
             );
 
             $charterer_invoice = OpsChartererInvoice::create($charterer_invoice_info);
             $charterer_invoice->opsChartererInvoiceLines()->createMany($request->opsChartererInvoiceLines);
             DB::commit();
             return response()->success('Charterer invoice added successfully.', $charterer_invoice, 201);
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
      * @param  OpsChartererInvoice  $charterer_invoice
      * @return JsonResponse
      */
     public function show(OpsChartererInvoice $charterer_invoice): JsonResponse
     {
         $charterer_invoice->load('opsChartererProfile','opsChartererContract','opsChartererInvoiceLines');
         try
         {
             return response()->success('Successfully retrieved charterer invoice.', $charterer_invoice, 200);
         }
         catch (QueryException $e)
         {
             return response()->error($e->getMessage(), 500);
         }
 
     }
 
 
       /**
      * Update the specified resource in storage.
      *
      * @param OpsChartererInvoiceRequest $request
      * @param  OpsChartererInvoice  $charterer_invoice
      * @return JsonResponse
      */
     public function update(OpsChartererInvoiceRequest $request, OpsChartererInvoice $charterer_invoice): JsonResponse
     {
         try {
             DB::beginTransaction();
             $charterer_invoice_info = $request->except(
                 '_token',
                 'opsChartererInvoiceLines',
             );
            
             $charterer_invoice->update($charterer_invoice_info);       
             $charterer_invoice->opsChartererInvoiceLines()->delete();
             $charterer_invoice->opsChartererInvoiceLines()->createMany($request->opsChartererInvoiceLines);
             DB::commit();
             return response()->success('Charterer invoice updated successfully.', $charterer_invoice, 200);
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
      * @param  OpsChartererInvoice  $charterer_invoice
      * @return \Illuminate\Http\JsonResponse
      */
     public function destroy(OpsChartererInvoice $charterer_invoice): JsonResponse
     {
         try
         {
             $charterer_invoice->opsChartererInvoiceLines()->delete();
             $charterer_invoice->delete();
 
             return response()->json([
                 'message' => 'Successfully deleted charterer invoice.',
             ], 204);
         }
         catch (QueryException $e)
         {
             return response()->error($e->getMessage(), 500);
         }
     }
     
     public function getChartererInvoiceName(){
         try {
             $charterer_invoices = OpsChartererInvoice::with('opsChartererInvoiceLines')->latest()->paginate(15);
             
             return response()->success('Successfully retrieved cargo tariffs name.', collect($charterer_invoices->pluck('tariff_name'))->unique()->values()->all(), 200);
         } catch (QueryException $e){
             return response()->error($e->getMessage(), 500);
         }
     }
 

}
