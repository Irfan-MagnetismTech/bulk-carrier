<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Modules\Operations\Entities\OpsVoyage;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsChartererInvoice;
use Modules\Operations\Entities\OpsChartererInvoiceVoyage;
use Modules\Operations\Http\Requests\OpsChartererInvoiceRequest;

class OpsChartererInvoiceController extends Controller
{
   use HasRoles;
  
   function __construct()
   {
       $this->middleware('permission:ops-charterer-invoice-create|ops-charterer-invoice-edit|ops-charterer-invoice-view|ops-charterer-invoice-delete', ['only' => ['index','show']]);
       $this->middleware('permission:ops-charterer-invoice-create', ['only' => ['store']]);
       $this->middleware('permission:ops-charterer-invoice-edit', ['only' => ['update']]);
       $this->middleware('permission:ops-charterer-invoice-delete', ['only' => ['destroy']]);
   }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
    public function index(Request $request) : JsonResponse
    {
        try {
            $charterer_invoices = OpsChartererInvoice::with('opsChartererProfile','opsChartererContract.opsChartererContractsFinancialTerms.opsCargoTariff.opsCargoType','opsChartererInvoiceOthers','opsChartererInvoiceServices','opsChartererInvoiceVoyages.opsVoyage.opsVoyageSectors',
            'opsChartererContract.opsVessel')
            ->globalSearch($request->all());
            
            return response()->success('Data retrieved successfully.', $charterer_invoices, 200);
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
        // return response()->json($request->all(),422);

        if(isset($request->opsChartererInvoiceVoyages)){
            $voyage_ids= [];
            foreach($request->opsChartererInvoiceVoyages as $key=>$voyage){
                $voyage_ids[]=$voyage['ops_voyage_id'];
            }

            if(count($voyage_ids) != count(array_values(array_unique($voyage_ids)))) {
                    $error= [
                        'message'=>'Same voyage cannot be billed more than once.',
                        'errors'=>[
                            'Voyage'=>['Same voyage cannot be billed more than once.',]
                            ]
                        ];
                    return response()->json($error, 422);
            }

            $isBilled = OpsChartererInvoiceVoyage::whereIn('ops_voyage_id', $voyage_ids)->get();

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
            $charterer_invoice_info = $request->except(
                '_token',
                'opsChartererInvoiceVoyages',
                'opsChartererInvoiceOthers',
                'opsChartererInvoiceServices'
            );

            $charterer_invoice = OpsChartererInvoice::create($charterer_invoice_info);
            $charterer_invoice->opsChartererInvoiceVoyages()->createMany($request->opsChartererInvoiceVoyages);
            $charterer_invoice->opsChartererInvoiceOthers()->createMany($request->opsChartererInvoiceOthers);
            $charterer_invoice->opsChartererInvoiceServices()->createMany($request->opsChartererInvoiceServices);
            DB::commit();
            return response()->success('Data added successfully.', $charterer_invoice, 201);
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
        $charterer_invoice->load('opsChartererProfile','opsChartererContract.opsChartererContractsFinancialTerms.opsCargoTariff.opsCargoType','opsChartererInvoiceOthers','opsChartererInvoiceServices','opsChartererInvoiceVoyages.opsVoyage.opsVoyageSectors',
        'opsChartererContract.opsVessel', 'opsChartererInvoiceVoyages.opsVoyage.opsCargoType','opsChartererContract.opsChartererInvoices','opsChartererContract.dayWiseInvoices');
        try
        {
            return response()->success('Data retrieved successfully.', $charterer_invoice, 200);
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

        if(isset($request->opsChartererInvoiceVoyages)){
            $voyage_ids= [];
            foreach($request->opsChartererInvoiceVoyages as $key=>$voyage){
                $voyage_ids[]=$voyage['ops_voyage_id'];
            }
            
            $currentVoyages = $charterer_invoice->opsChartererInvoiceVoyages->pluck('ops_voyage_id');

            $notMatchedInArray1 = array_diff($voyage_ids, $currentVoyages->toArray());

            if(count($voyage_ids) != count(array_values(array_unique($voyage_ids)))) {
                $error= [
                    'message'=>'Same voyage cannot be billed more than once.',
                    'errors'=>[
                        'Voyage'=>['Same voyage cannot be billed more than once.',]
                        ]
                    ];
                return response()->json($error, 422);
            }

            $isBilled = OpsChartererInvoiceVoyage::whereIn('ops_voyage_id', $notMatchedInArray1)->get();
           
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
            $charterer_invoice_info = $request->except(
                '_token',
                'opsChartererInvoiceVoyages',
                'opsChartererInvoiceOthers',
                'opsChartererInvoiceServices',
            );
        
            $charterer_invoice->update($charterer_invoice_info);
            $charterer_invoice->opsChartererInvoiceVoyages()->delete();     
            $charterer_invoice->opsChartererInvoiceVoyages()->createMany($request->opsChartererInvoiceVoyages);
            $charterer_invoice->opsChartererInvoiceOthers()->delete();
            $charterer_invoice->opsChartererInvoiceOthers()->createMany($request->opsChartererInvoiceOthers);
            $charterer_invoice->opsChartererInvoiceServices()->delete();
            $charterer_invoice->opsChartererInvoiceServices()->createMany($request->opsChartererInvoiceServices);
            DB::commit();
            return response()->success('Data updated successfully.', $charterer_invoice, 202);
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
            DB::beginTransaction();            
            $charterer_invoice->opsChartererInvoiceVoyages()->delete();
            $charterer_invoice->opsChartererInvoiceOthers()->delete();
            $charterer_invoice->opsChartererInvoiceServices()->delete();
            $charterer_invoice->delete();
            DB::commit();

            return response()->json([
                'message' => 'Data deleted successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->json($charterer_invoice->preventDeletionIfRelated(), 422);  
        }
    }
     
    public function getChartererInvoiceName(){
        try {
            $charterer_invoices = OpsChartererInvoice::with('opsChartererProfile','opsChartererContract','opsChartererInvoiceOthers','opsChartererInvoiceServices','opsChartererInvoiceVoyages.opsVoyage.opsVoyageSectors')
            ->latest()
            ->get();
            
            return response()->success('Data retrieved successfully.', $charterer_invoices, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getVoyageByContract(Request $request): JsonResponse
    {
        $voyages= OpsVoyage::with('opsVoyageSectors','opsCargoType','opsContractAssign')->whereHas('opsContractAssign',function($item){
            return $item->where('ops_charterer_contract_id', request()->contract_id);
        })
        ->get();
     
        $voyages->map(function($voyage){  
            $cargo_quantity = 0;
            if ($voyage->opsVoyageSectors->sum('final_received_qty') > 0) {
                $cargo_quantity = $voyage->opsVoyageSectors->sum('final_received_qty');
            } elseif ($voyage->opsVoyageSectors->sum('final_survey_qty') > 0) {
                $cargo_quantity = $voyage->opsVoyageSectors->sum('final_survey_qty');
            } elseif ($voyage->opsVoyageSectors->sum('boat_note_qty') > 0) {
                $cargo_quantity = $voyage->opsVoyageSectors->sum('boat_note_qty');
            } elseif ($voyage->opsVoyageSectors->sum('initial_survey_qty') > 0) {
                $cargo_quantity = $voyage->opsVoyageSectors->sum('initial_survey_qty');
            }
        
            $voyage['cargo_quantity'] = $cargo_quantity;
        
            return $voyage;
                        
        });
        
       
        try {            
            return response()->success('Data retrieved successfully.', $voyages, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

 

}
