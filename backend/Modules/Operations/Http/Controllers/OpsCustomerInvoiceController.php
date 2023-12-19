<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsCustomerInvoice;
use Modules\Operations\Entities\OpsCustomerInvoiceVoyage;
use Modules\Operations\Http\Requests\OpsCustomerInvoiceRequest;

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
            $customerInvoices = OpsCustomerInvoice::with('opsCustomer','opsCustomerInvoiceVoyages.opsVoyage.opsContractTariffs.opsCargoTariff','opsCustomerInvoiceVoyages.opsVoyage.opsContractTariffs.opsVoyageSectors','opsCustomerInvoiceVoyages.opsVoyage.opsVoyageSectors','opsCustomerInvoiceVoyages.opsVessel','opsCustomerInvoiceVoyages','opsCustomerInvoiceOthers','opsCustomerInvoiceServices')
           ->globalSearch($request->all());
            
            return response()->success('Data retrieved successfully.', $customerInvoices, 200);
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
        //  dd($request->opsCustomerInvoiceLines);
        if(isset($request->opsCustomerInvoiceVoyages)){
            $voyage_ids= [];
            foreach($request->opsCustomerInvoiceVoyages as $key=>$voyage){
                $voyage_ids[]=$voyage['ops_voyage_id'];
            }    

            $isBilled = OpsCustomerInvoiceVoyage::whereIn('ops_voyage_id', $voyage_ids)->get();

            if(count($isBilled) > 0) {
                $error= [
                    'message'=>'One or more voyages are already billed.',
                    'errors'=>[
                        'Voyage'=>['One or more voyages are already billed.',]
                        ]
                    ];
                return response()->json($error, 422);
            }
        }

        try {
            DB::beginTransaction();
            $customerInvoiceInfo = $request->except(
                '_token',
                'opsCustomerInvoiceVoyages',
                'opsCustomerInvoiceOthers',
                'opsCustomerInvoiceServices',
            );

            $customerInvoice = OpsCustomerInvoice::create($customerInvoiceInfo);
            $customerInvoice->opsCustomerInvoiceVoyages()->createMany($request->opsCustomerInvoiceVoyages);
            $customerInvoice->opsCustomerInvoiceOthers()->createMany($request->opsCustomerInvoiceOthers);
            $customerInvoice->opsCustomerInvoiceServices()->createMany($request->opsCustomerInvoiceServices);
            DB::commit();
            return response()->success('Data added successfully.', $customerInvoice, 201);
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
        $customer_invoice->load('opsCustomer','opsCustomerInvoiceVoyages.opsVoyage.opsContractTariffs.opsCargoTariff','opsCustomerInvoiceVoyages.opsVoyage.opsContractTariffs.opsVoyage.opsVoyageSectors','opsCustomerInvoiceVoyages.opsVoyage.opsVoyageSectors','opsCustomerInvoiceVoyages.opsVoyage.opsVessel','opsCustomerInvoiceVoyages.opsVoyage.opsCargoType','opsCustomerInvoiceVoyages','opsCustomerInvoiceOthers','opsCustomerInvoiceServices');
               
        
        collect($customer_invoice->opsCustomerInvoiceVoyages)->map(function($invoiceVoyages){
            $invoiceVoyages->opsVoyage->opsContractTariffs->map(function($contract) use ($invoiceVoyages){
                // dd($contract->opsVoyage->opsVoyageSectors);
                // $contract->opsVoyageSectors['tariff_name'] =$contract->where('pol_pod', $contract->opsVoyageSectors['pol_pod'])?->first()?->opsCargoTariff?->tariff_name;
                // $contract->opsVoyageSectors['tariff_id'] =$contract->where('pol_pod', $contract->opsVoyageSectors['pol_pod'])?->first()?->opsCargoTariff?->id;
                // $contract->opsVoyageSectors['total_rate'] =$contract->where('pol_pod', $contract->opsVoyageSectors['pol_pod'])?->first()?->total_rate;

                // $contract['amount'] = $contract->total_rate * $contract->quantity;
                $invoiceVoyages['opsCargoType']= $invoiceVoyages->opsVoyage->opsCargoType;
                $invoiceVoyages['opsVessel']= $invoiceVoyages->opsVoyage->opsVessel;
                $contract->opsVoyage->opsVoyageSectors->map(function($item) use($contract) {
                    if($contract['pol_pod']==$item['pol_pod']){
                        $item['opsCargoTariff'] = $contract->opsCargoTariff;
                    }
                    return $item;
                });
            });

            $invoiceVoyages['contractTariff']=$invoiceVoyages->opsVoyage;
            return $invoiceVoyages;
        });

        try
        {
        return response()->success('Data retrieved successfully.', $customer_invoice, 200);
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
        if(isset($request->opsCustomerInvoiceVoyages)){
            $voyage_ids= [];
            foreach($request->opsCustomerInvoiceVoyages as $key=>$voyage){
                $voyage_ids[]=$voyage['ops_voyage_id'];
            }
            
            $currentVoyages = $customer_invoice->opsCustomerInvoiceVoyages->pluck('ops_voyage_id');

            $notMatchedInArray1 = array_diff($voyage_ids, $currentVoyages->toArray());

            $isBilled = OpsCustomerInvoiceVoyage::whereIn('ops_voyage_id', $notMatchedInArray1)->get();


            if(count($isBilled) > 0) {
                $error= [
                    'message'=>'One or more voyages are already billed.',
                    'errors'=>[
                        'Voyage'=>['One or more voyages are already billed.',]
                        ]
                    ];
                return response()->json($error, 422);
            }
        }

        try {
        DB::beginTransaction();            
        $customerInvoiceInfo = $request->except(
            '_token',
            'opsCustomerInvoiceVoyages',
            'opsCustomerInvoiceOthers',
            'opsCustomerInvoiceServices',
        );
    
        $customer_invoice->update($customerInvoiceInfo);
        $customer_invoice->opsCustomerInvoiceVoyages()->createUpdateOrDelete($request->opsCustomerInvoiceVoyages);
        $customer_invoice->opsCustomerInvoiceOthers()->createUpdateOrDelete($request->opsCustomerInvoiceOthers);
        $customer_invoice->opsCustomerInvoiceServices()->createUpdateOrDelete($request->opsCustomerInvoiceServices);

        DB::commit();
        return response()->success('Data updated successfully.', $customer_invoice, 202);
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
            $customer_invoice->opsCustomerInvoiceVoyages()->delete();
            $customer_invoice->opsCustomerInvoiceOthers()->delete();
            $customer_invoice->opsCustomerInvoiceServices()->delete();
            $customer_invoice->delete();

            return response()->json([
                'message' => 'Data deleted successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
