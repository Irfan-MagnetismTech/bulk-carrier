<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Modules\SupplyChain\Entities\ScmCs;
use Modules\SupplyChain\Entities\ScmCsMaterial;
use Modules\SupplyChain\Entities\ScmCsMaterialVendor;
use Modules\SupplyChain\Entities\ScmCsVendor;
use Modules\SupplyChain\Http\Requests\ScmCsRequest;
use Modules\SupplyChain\Http\Requests\ScmQuotationRequest;
use Modules\SupplyChain\Http\Requests\SupplierSelectionRequest;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Services\UniqueId;

class ScmCsController extends Controller
{
    function __construct()
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
                ->with('scmPr', 'scmWarehouse', 'selectedVendors.scmVendor', 'scmCsMaterials.scmMaterial','scmCsVendors.scmVendor' ,'scmCsMaterialVendors.scmMaterial', 'scmCsMaterialVendors.scmPr')
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
        $requestData['ref_no'] = UniqueId::generate(ScmCs::class, 'CS');
        try {
            DB::beginTransaction();
            $scmCs = ScmCs::create($requestData);

            foreach ($request->scmCsMaterials as $key => $value) {
                ScmCsMaterial::create([
                    'scm_cs_id' => $scmCs->id,
                    'scm_pr_id' => $value['scm_pr_id'],
                    'scm_material_id' => $value['scm_material_id'],
                    'cs_composite_key' => CompositeKey::generate(null, $scmCs->id, 'cs', $value['scm_material_id'], $value['scm_pr_id']),
                    'pr_composite_key' => $value['pr_composite_key'],
                    'unit' => $value['unit'],
                    'quantity' => $value['quantity'],
                ]);
            }

            DB::commit();
            return response()->success('Data created succesfully', $scmCs, 201);
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
        // $materialCs->load('scmPr', 'scmWarehouse');
        $materialCs->load('scmCsMaterials.scmMaterial', 'scmCsMaterials.scmPr', 'scmWarehouse');
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
            $materialCs->scmCsMaterials->each(function ($item) {
                $item->delete();
            });
            foreach ($request->scmCsMaterials as $key => $value) {
                ScmCsMaterial::create([
                    'scm_cs_id' => $materialCs->id,
                    'scm_material_id' => $value['scm_material_id'],
                    'cs_composite_key' => CompositeKey::generate(null, $materialCs->id, 'cs', $value['scm_material_id'], $value['scm_pr_id']),
                    'pr_composite_key' => $value['pr_composite_key'],
                    'unit' => $value['unit'],
                    'quantity' => $value['quantity'],
                ]);
            }
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
            $materialCs->scmCsMaterials->each(function ($item) {
                $item->delete();
            });
            $materialCs->delete();
            DB::commit();
            return response()->success('Data deleted succesfully', null, 203);
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json($materialCs->preventDeletionIfRelated(), 422);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function getCsWiseData($id)
    {
        $materialCs = ScmCs::find($id);
        // $materialCs->load('scmPr', 'scmWarehouse');
        $materialCs->load('scmCsMaterials.scmMaterial', 'scmPr', 'scmCsMaterials.scmPr', 'scmWarehouse');
        //scmCsMaterials groupBy ['scm_material_id','scm_pr_id']
        $data = $materialCs->scmCsMaterials->groupBy(['scm_material_id'])->values()->all();
        data_forget($materialCs, 'scmCsMaterials');

        $materialCs['scmCsMaterials'] = $data;


        try {
            return response()->success('Detail data', $materialCs, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }



    public function getQuotations(Request $request)
    {
        $scmCs = ScmCsVendor::query()
            ->with('scmCs', 'scmVendor.scmVendorContactPerson', 'scmCsMaterialVendors')
            ->where('scm_cs_id', $request->cs_id)
            ->get();

        return response()->success('Data list', $scmCs, 200);
    }

    public function storeQuotation(ScmQuotationRequest $request)
    {
        try {
            // ScmCs::find($request->scm_cs_id)->update(['status' => 'quotation']);
            $scmCs = ScmCs::find($request->scm_cs_id);
            $requestData = $request->only(
                'scm_cs_id',
                'scm_vendor_id',
                'vendor_type',
                'sourcing',
                'date_of_rfq',
                'quotation_received_date',
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

            DB::beginTransaction();

            $scmCsVendor = ScmCsVendor::create($requestData);
            $adadas = [];
            foreach ($request->scmCsMaterialVendors as $key => $values) {
                $negotiatedprice = $values[0]['negotiated_price'] ?? 0;
                $offerprice = $values[0]['offered_price'] ?? 0;
                $brand = $values[0]['brand'] ?? null;
                $unit = $values[0]['unit'] ?? null;
                $model = $values[0]['model'] ?? null;
                $origin = $values[0]['origin'] ?? null;
                $stock_type = $values[0]['stock_type'] ?? null;
                $manufacturing_days = $values[0]['manufacturing_days'] ?? null;

                foreach ($values as $key1 => $value) {
                    $csMaterial = ScmCsMaterial::where([
                        'scm_cs_id' => $scmCs->id,
                        'scm_material_id' => $value['scm_material_id']
                    ])->first();

                    ScmCsMaterialVendor::create(
                        [
                            'scm_cs_id' => $scmCs->id,
                            'scm_cs_vendor_id' => $scmCsVendor->id ?? null,
                            'scm_vendor_id' => $scmCsVendor->scm_vendor_id ?? null,
                            'scm_cs_material_id' => $csMaterial->id,
                            'scm_pr_id' => $value['scm_pr_id'] ?? null,
                            'scm_material_id' => $value['scm_material_id'] ?? null,
                            'brand' => $brand ?? null,
                            'unit' => $unit ?? null,
                            'model' => $model ?? null,
                            'origin' => $origin ?? null,
                            'stock_type' => $stock_type ?? null,
                            'manufacturing_days' => $manufacturing_days ?? null,
                            'offered_price' => $offerprice ?? null,
                            'negotiated_price' => $negotiatedprice ?? null,
                        ]
                    );
                }
                // $csMaterial = ScmCsMaterial::where(['scm_cs_id' => $scmCs->id,
                //     'scm_material_id' => $value['scm_material_id']
                // ])->first();

                // ScmCsMaterialVendor::create([
                //     'scm_cs_id' => $scmCs->id,
                //     'scm_cs_vendor_id' => $scmCsVendor->id,
                //     'scm_vendor_id' => $scmCsVendor->scm_vendor_id,
                //     'scm_cs_material_id' => $csMaterial->id,
                //     'scm_pr_id' => $request->scmCsMaterialVendors[$key]['scm_pr_id'] ?? null,
                //     'scm_material_id' => $request->scmCsMaterialVendors[$key]['scm_material_id'] ?? null,
                //     'brand' => $request->scmCsMaterialVendors[$key]['brand'] ?? null,
                //     'unit' => $request->scmCsMaterialVendors[$key]['unit'] ?? null,
                //     'model' => $request->scmCsMaterialVendors[$key]['model'] ?? null,
                //     'origin' => $request->scmCsMaterialVendors[$key]['origin'] ?? null,
                //     'stock_type' => $request->scmCsMaterialVendors[$key]['stock_type'] ?? null,
                //     'manufaturing_days' => $request->scmCsMaterialVendors[$key]['manufaturing_days'] ?? null,
                //     'unit' => $request->scmCsMaterialVendors[$key]['unit'] ?? null,
                //     'offered_price' => $request->scmCsMaterialVendors[$key]['offered_price'] ?? null,
                //     'negotiated_price' => $request->scmCsMaterialVendors[$key]['negotiated_price'] ?? null,
                // ]);
            }
            DB::commit();
            return response()->success('Data created succesfully', $scmCsVendor, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    public function showQuotation($id)
    {
        $scmCsVendor = ScmCsVendor::with('scmCs', 'scmVendor.scmVendorContactPerson', 'scmCsMaterialVendors.scmMaterial', 'scmCsMaterialVendors.scmPr')->find($id);
        $scmCsMaterialVendors = $scmCsVendor->scmCsMaterialVendors->groupBy(['scm_material_id'])->values()->all();
        data_forget($scmCsVendor, 'scmCsMaterialVendors');
        $scmCsVendor['scmCsMaterialVendors'] = $scmCsMaterialVendors;
        try {
            return response()->success('Detail data', $scmCsVendor, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    // update quotation
    public function updateQuotation(ScmQuotationRequest $request, $id)
    {
        try {
            // return response()->json( $request->all(), 422);
            $scmCsVendor = ScmCsVendor::find($id);
            $requestData = $request->only(
                'scm_vendor_id',
                'vendor_type',
                'sourcing',
                'date_of_rfq',
                'quotation_received_date',
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

            DB::beginTransaction();

            $scmCsVendor->update($requestData);
            $scmCsVendor->scmCsMaterialVendors->each(function ($item) {
                $item->delete();
            });

            //             foreach ($request->scmCsMaterialVendors as $key => $value) {
            //                 $csMaterial = ScmCsMaterial::where([
            //                     'scm_cs_id' => $scmCsVendor->scm_cs_id,
            //                     'scm_material_id' => $value['scm_material_id']
            //                 ])->first();

            //                 ScmCsMaterialVendor::create([
            //                     'scm_cs_id' => $scmCsVendor->scm_cs_id,
            //                     'scm_cs_vendor_id' => $scmCsVendor->id,
            //                     'scm_vendor_id' => $scmCsVendor->scm_vendor_id,
            //                     'scm_cs_material_id' => $csMaterial->id,
            //                     'scm_material_id' => $request->scmCsMaterialVendors[$key]['scm_material_id'] ?? null,
            //                     'scm_pr_id' => $request->scmCsMaterialVendors[$key]['scm_pr_id'] ?? null,
            //                     'brand' => $request->scmCsMaterialVendors[$key]['brand'] ?? null,
            //                     'unit' => $request->scmCsMaterialVendors[$key]['unit'] ?? null,
            //                     'model' => $request->scmCsMaterialVendors[$key]['model'] ?? null,
            //                     'origin' => $request->scmCsMaterialVendors[$key]['origin'] ?? null,
            //                     'stock_type' => $request->scmCsMaterialVendors[$key]['stock_type'] ?? null,
            //                     'manufaturing_days' => $request->scmCsMaterialVendors[$key]['manufaturing_days'] ?? null,
            //                     'unit' => $request->scmCsMaterialVendors[$key]['unit'] ?? null,
            //                     'offered_price' => $request->scmCsMaterialVendors[$key]['offered_price'] ?? null,
            //                     'negotiated_price' => $request->scmCsMaterialVendors[$key]['negotiated_price'] ?? null,
            //                 ]);

            // ///need to add selected supplier later
            // }

            foreach ($request->scmCsMaterialVendors as $key => $values) {
                $negotiatedprice = $values[0]['negotiated_price'] ?? 0;
                $offerprice = $values[0]['offered_price'] ?? 0;
                $brand = $values[0]['brand'] ?? null;
                $unit = $values[0]['unit'] ?? null;
                $model = $values[0]['model'] ?? null;
                $origin = $values[0]['origin'] ?? null;
                $stock_type = $values[0]['stock_type'] ?? null;
                $manufacturing_days = $values[0]['manufacturing_days'] ?? null;

                foreach ($values as $key1 => $value) {
                    $csMaterial = ScmCsMaterial::where([
                        'scm_cs_id' => $scmCsVendor->scm_cs_id,
                        'scm_material_id' => $value['scm_material_id']
                    ])->first();
                    ScmCsMaterialVendor::create(
                        [
                            'scm_cs_id' => $scmCsVendor->scm_cs_id,
                            'scm_cs_vendor_id' => $scmCsVendor->id ?? null,
                            'scm_vendor_id' => $scmCsVendor->scm_vendor_id ?? null,
                            'scm_cs_material_id' => $csMaterial->id,
                            'scm_pr_id' => $value['scm_pr_id'] ?? null,
                            'scm_material_id' => $value['scm_material_id'] ?? null,
                            'brand' => $brand ?? null,
                            'unit' => $unit ?? null,
                            'model' => $model ?? null,
                            'origin' => $origin ?? null,
                            'stock_type' => $stock_type ?? null,
                            'manufacturing_days' => $manufacturing_days ?? null,
                            'offered_price' => $offerprice ?? null,
                            'negotiated_price' => $negotiatedprice ?? null,
                        ]
                    );
                }
            }
            DB::commit();
            return response()->success('Data updated succesfully', $scmCsVendor, 202);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCsData($csId)
    {
        $scmCs = ScmCs::with('scmCsMaterials.scmMaterial', 'scmCsMaterials.scmPr', 'scmPr', 'scmWarehouse')->find($csId);
        $CsVendor = ScmCsVendor::with('scmVendor')->where('scm_cs_id', $csId)->get()->groupBy('scm_vendor_id');
        $scmCs['scmCsVendor'] = $CsVendor;
        $csVendorMaterial = ScmCsMaterialVendor::with('scmCsMaterial.scmMaterial', 'scmCsMaterial.scmPr')->where('scm_cs_id', $csId)->get()->groupBy(['scm_material_id', 'scm_pr_id', 'scm_vendor_id']);
        $scmCs['scmCsMaterialVendor'] = $csVendorMaterial;
        $csMaterial = ScmCsMaterial::with('scmMaterial', 'scmPr')->where('scm_cs_id', $csId)->get()->groupBy(['scm_material_id', 'scm_pr_id']);
        $scmCs['scmCsMaterial'] = $csMaterial;

        try {
            return response()->success('Detail data', $scmCs, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function selectedSupplierstore(SupplierSelectionRequest $request)
    {

        $data = $request->only('id', 'selection_ground', 'auditor_remarks_date', 'auditor_remarks', 'scmCsVendor');

        try {
            $cs = ScmCs::find($data['id']);
            $cs->update(
                [
                    'selection_ground' => $data['selection_ground'],
                    'auditor_remarks_date' => $data['auditor_remarks_date'],
                    'auditor_remarks' => $data['auditor_remarks'],
                ]
            );
            foreach ($data['scmCsVendor'] as $key => $value) {
                $csVendor = ScmCsVendor::find($value[0]['id']);
                $csVendor->update(['is_selected' => $value[0]['is_selected']]);
            }

            return response()->success('Data updated succesfully', $data, 202);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchMaterialCs(Request $request)
    {
        if (isset($request->searchParam)) {
            $cs = ScmCs::query()
                ->with('scmCsVendors', 'scmCsMaterials', 'scmCsMaterialVendors')
                ->where(function ($query) use ($request) {
                    $query->where('ref_no', 'like', '%' . $request->searchParam . '%')
                        ->where('business_unit', $request->business_unit)
                        ->where('scm_warehouse_id', $request->scm_warehouse_id)
                        ->where('purchase_center', $request->purchase_center);
                })
                // ->where('ref_no', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        } else {
            $cs = ScmCs::query()
                ->with('scmCsVendors', 'scmCsMaterials', 'scmCsMaterialVendors')
                ->where(function ($query) use ($request) {
                    $query->where('business_unit', $request->business_unit)
                        ->where('scm_warehouse_id', $request->scm_warehouse_id)
                        ->where('purchase_center', $request->purchase_center);
                })
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        }

        return response()->success('Search result', $cs, 200);
    }

    public function csWiseVendorList(Request $request)
    {
        $csVendor = ScmCsVendor::query()
            ->with('scmVendor')
            ->where('scm_cs_id', $request->cs_id)
            ->get()
            ->map(function ($item) {
                return $item->scmVendor;
            });
        return response()->success('Search result', $csVendor, 200);
    }
}
