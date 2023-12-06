<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmCs;
use Modules\SupplyChain\Entities\ScmCsMaterialVendor;
use Modules\SupplyChain\Entities\ScmCsVendor;
use Modules\SupplyChain\Http\Requests\ScmCsRequest;
use Modules\SupplyChain\Http\Requests\ScmQuotationRequest;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Services\UniqueId;

class ScmCsController extends Controller
{
    function __construct(private UniqueId $uniqueId, private CompositeKey $compositeKey)
    {
        //     $this->middleware('permission:charterer-contract-create|charterer-contract-edit|charterer-contract-show|charterer-contract-delete', ['only' => ['index','show']]);
        //     $this->middleware('permission:charterer-contract-create', ['only' => ['store']]);
        //     $this->middleware('permission:charterer-contract-edit', ['only' => ['update']]);
        //     $this->middleware('permission:charterer-contract-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $scmCs = ScmCs::query()
                ->with('scmPr', 'scmWarehouse')
                ->globalSearch(request()->all());

            
            return response()->success('Data list', $scmCs, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ScmCsRequest $request)
    {
        $requestData = $request->except('ref_no');
        $requestData['ref_no'] = $this->uniqueId->generate(ScmCs::class, 'CS');
        try {
            DB::beginTransaction();
            $scmMi = ScmCs::create($requestData);
            //throw exception if creating fail


            DB::commit();
            return response()->success('Data created succesfully', $scmMi, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $materialCs = ScmCs::find($id);
        $materialCs->load('scmPr', 'scmWarehouse');
        try {
            return response()->success('Detail data', $materialCs, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    
       
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(ScmCsRequest $request, $id)
    {
        $materialCs = ScmCs::find($id);
        $requestData = $request->except('ref_no');
        try {
            DB::beginTransaction();
            $materialCs->update($requestData);
            DB::commit();
            return response()->success('Data updated succesfully', $materialCs, 202);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $materialCs = ScmCs::find($id);
        try {
            DB::beginTransaction();
            $materialCs->delete();
            DB::commit();
            return response()->success('Data deleted succesfully', null, 203);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getQuotations(Request $request)
    {
        $scmCs = ScmCsVendor::query()
            ->with('scmPr', 'scmWarehouse')
            ->where('id',$request->cs_id)
            ->get();

        return response()->success('Data list', $scmCs, 200);
    }

    public function storeQuotation(ScmQuotationRequest $request)
    {
        try {
            DB::beginTransaction();
            // ScmCs::find($request->scm_cs_id)->update(['status' => 'quotation']);
            $scmCs = ScmCs::find($request->scm_cs_id);
            $requestData = $request->only(
                'scm_cs_id',
                'scm_vendor_id',
                'vendor_type',
                'sourcing',
                'date_of_rfq',
                'quotations_received_date',
                'quotation_ref',
                'quotation_date',
                'quotation_validity',
                'payment_method',
                'currency',
                'carring_cost_bear_by',
                'unloading_cost_bear_by',
                'vat',
                'ait',
                'credit_term',
                'quotation_shipment_date',
                'estimated_shipment',
                'port_of_loading',
                'port_of_discharge',
                'port_of_shipment',
                'mode_of_shipment',
                'delivery_term',
                'terms_and_condition',
                'remarks',
            );

            $scmCsVendor = ScmCsVendor::create($requestData);
            $scmMaterial = [];
            foreach ($request->scmCsVendorMaterial as $key => $value) {
                $scmMaterial[] = $value->scm_material_id;
            }
            $scmCs->scmCsMaterials()->createMany($scmMaterial);
            $ScmCsVendorMaterilArray = $this->setScmVendorMaterial()
            DB::commit();
            return response()->success('Data created succesfully', $scmMi, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }

    }

   
    private function setScmVendorMaterial($scmVendorId, $materialId, $materialQty)
    {
        $scmVendorMaterial = ScmCsMaterialVendor::create([
            'scm_material_id' => ,
            brand: null,
            model: null,
            origin: null,
            stock_type: null,
            manufaturing_days: null,
            unit: null,
            offered_price: null,
            negotiated_price: null,
        ]);
        return $scmVendorMaterial;
    }


}
