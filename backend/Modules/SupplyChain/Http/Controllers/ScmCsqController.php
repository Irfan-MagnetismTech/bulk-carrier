<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Modules\SupplyChain\Entities\ScmCs;
use Modules\SupplyChain\Entities\ScmCsVendor;
use Modules\SupplyChain\Entities\ScmCsMaterial;
use Modules\SupplyChain\Entities\ScmCsMaterialVendor;
use Modules\SupplyChain\Http\Requests\ScmQuotationRequest;

class ScmCsqController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:scm-material-cs-qoutation-view', ['only' => ['getQuotations', 'showQuotation']]);
        $this->middleware('permission:scm-material-cs-qoutation-create', ['only' => ['storeCsLandedCost']]);
        $this->middleware('permission:scm-material-cs-qoutation-edit', ['only' => ['updateQuotation']]);
        $this->middleware('permission:scm-material-cs-qoutation-delete', ['only' => ['deleteQuotation']]);
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
                'delivery_time',
                'terms_and_condition',
                'remarks',
                'stock_type',
                'manufacturing_days',
                'warranty',
                'freight',
                'total_negotiated_price',
                'total_offered_price',
                'grand_total_negotiated_price',
                'grand_total_offered_price'
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
                $installation_and_commission = $values[0]['installation_and_commission'] ?? null;
                $certification = $values[0]['certification'] ?? null;
                $stock_type = $values[0]['stock_type'] ?? null;
                $warranty_period = $values[0]['warranty_period'] ?? null;
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
                            'installation_and_commission' => $installation_and_commission ?? null,
                            'certification' => $certification ?? null,
                            'stock_type' => $stock_type ?? null,
                            'warranty_period' => $warranty_period ?? null,
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
        $scmCsMaterialVendors = $scmCsVendor->scmCsMaterialVendors->map(function ($item) {
            $data = $item;
            $data['quantity'] = ScmCsMaterial::where(['scm_cs_id' => $item->scm_cs_id, 'scm_material_id' => $item->scm_material_id])->sum('quantity');
            return $data;
        })->groupBy(['scm_material_id'])->values()->all();

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
                'delivery_time',
                'terms_and_condition',
                'remarks',
                'stock_type',
                'manufacturing_days',
                'warranty',
                'freight',
                'total_negotiated_price',
                'total_offered_price',
                'grand_total_negotiated_price',
                'grand_total_offered_price'
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
                $installation_and_commission = $values[0]['installation_and_commission'] ?? null;
                $certification = $values[0]['certification'] ?? null;
                $stock_type = $values[0]['stock_type'] ?? null;
                $warranty_period = $values[0]['warranty_period'] ?? null;
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
                            'installation_and_commission' => $installation_and_commission ?? null,
                            'certification' => $certification ?? null,
                            'stock_type' => $stock_type ?? null,
                            'manufacturing_days' => $manufacturing_days ?? null,
                            'warranty_period' => $warranty_period ?? null,
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

    public function deleteQuotation($id)
    {
        $scmCsVendor = ScmCsVendor::with('scmCsMaterialVendors')->find($id);

        try {
            DB::beginTransaction();

            $scmCsVendor->scmCsMaterialVendors->each(function ($item) {
                $item->delete();
            });
            $scmCsVendor->delete();

            DB::commit();
            return response()->success('Data deleted succesfully', null, 203);
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json($scmCsVendor->preventDeletionIfRelated(), 422);
        }
    }
}
