<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Modules\SupplyChain\Entities\ScmCs;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Entities\ScmPoItem;
use Modules\SupplyChain\Entities\ScmPrLine;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmCsVendor;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Entities\ScmCsMaterial;
use Modules\SupplyChain\Entities\ScmCsLandedCost;
use Modules\SupplyChain\Entities\ScmCsPaymentInfo;
use Modules\SupplyChain\Http\Requests\ScmCsRequest;
use Modules\SupplyChain\Entities\ScmCsStockQuantity;
use Modules\SupplyChain\Entities\ScmCsMaterialVendor;
use Modules\SupplyChain\Http\Requests\CsLandedCostRequest;
use Modules\SupplyChain\Http\Requests\ScmQuotationRequest;
use Modules\SupplyChain\Http\Requests\SupplierSelectionRequest;

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
                ->with('scmPo', 'scmWarehouse', 'selectedVendors.scmVendor', 'scmCsMaterials.scmMaterial', 'scmCsVendors.scmVendor', 'scmCsMaterialVendors.scmMaterial', 'scmCsMaterialVendors')
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
        try {
            DB::beginTransaction();
            $scmMi = ScmCs::create($requestData);

            foreach ($request->scmCsMaterials as $key => $value) {
                ScmCsMaterial::create([
                    'scm_cs_id' => $scmMi->id,
                    'scm_pr_id' => $value['scm_pr_id'],
                    'scm_material_id' => $value['scm_material_id'],
                    'cs_composite_key' => CompositeKey::generate(null, $scmMi->id, 'cs', $value['scm_material_id'], $value['scm_pr_id']),
                    'pr_composite_key' => $value['pr_composite_key'],
                    'unit' => $value['unit'],
                    'quantity' => $value['quantity'],
                ]);
                $pr = ScmPr::find($value['scm_pr_id']);
                if ($pr->status == 'Pending') {
                    $pr->update(['status' => 'WIP']);
                }
                $lineData = ScmPrLine::where('scm_pr_id', $value['scm_pr_id'])->where('pr_composite_key', $value['pr_composite_key'])->get();
                if ($lineData[0]->status == 'Pending') {
                    $lineData[0]->update(['status' => 'WIP']);
                }
            }

            foreach ($request->scmCsStockQuantity as $key2 => $value1) {
                ScmCsStockQuantity::create([
                    'scm_cs_id' => $scmMi->id,
                    'scm_material_id' => $value1['scm_material_id'],
                    'at_port' => $value1['at_port'],
                    'in_transit' => $value1['in_transit'],
                    'under_lc' => $value1['under_lc'],
                    'total_stock' => $value1['total_stock'],
                    'days_to_run' => $value1['days_to_run'],
                    'available_in_other_unit' => $value1['available_in_other_unit'],
                ]);
            }



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
        // $materialCs->load('scmPr', 'scmWarehouse');
        $materialCs->load('scmCsMaterials.scmMaterial', 'scmCsMaterials.scmPr', 'scmCsMaterials.scmPrLine', 'scmWarehouse', 'selectedVendors.scmCsPaymentInfo', 'scmPo', 'scmCsVendors', 'scmCsStockQuantity.scmMaterial');
        $data = $materialCs->scmCsMaterials->map(function ($item) {
            $item['pr_quantity'] = $item->scmPrLine->quantity;
            $item['max_quantity'] = $item->scmPrLine->quantity - $item->scmPrLine->scmCsmaterials->sum('quantity') + $item->quantity;
            return $item;
        });
        data_forget($materialCs, 'scmCsMaterials');

        $materialCs['scmCsMaterials'] = $data;
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

            $materialCs->scmCsStockQuantity->each(function ($item) {
                $item->delete();
            });
            foreach ($request->scmCsMaterials as $key => $value) {
                ScmCsMaterial::create([
                    'scm_cs_id' => $materialCs->id,
                    'scm_pr_id' => $value['scm_pr_id'],
                    'scm_material_id' => $value['scm_material_id'],
                    'cs_composite_key' => CompositeKey::generate(null, $materialCs->id, 'cs', $value['scm_material_id'], $value['scm_pr_id']),
                    'pr_composite_key' => $value['pr_composite_key'],
                    'unit' => $value['unit'],
                    'quantity' => $value['quantity'],
                ]);
                $pr = ScmPr::find($value['scm_pr_id']);
                if ($pr->status == 'Pending') {
                    $pr->update(['status' => 'WIP']);
                }
                $lineData = ScmPrLine::where('scm_pr_id', $value['scm_pr_id'])->where('pr_composite_key', $value['pr_composite_key'])->get();
                if ($lineData[0]->status == 'Pending') {
                    $lineData[0]->update(['status' => 'WIP']);
                }
            }

            foreach ($request->scmCsStockQuantity as $key2 => $value1) {
                ScmCsStockQuantity::create([
                    'scm_cs_id' => $materialCs->id,
                    'scm_material_id' => $value1['scm_material_id'],
                    'at_port' => $value1['at_port'],
                    'in_transit' => $value1['in_transit'],
                    'under_lc' => $value1['under_lc'],
                    'total_stock' => $value1['total_stock'],
                    'days_to_run' => $value1['days_to_run'],
                    'available_in_other_unit' => $value1['available_in_other_unit'],
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
        // $materialCs->load('scmCsMaterials.scmMaterial', 'scmCsMaterials.scmPr', 'scmWarehouse');
        $materialCs->load('scmCsMaterials.scmMaterial', 'scmCsMaterials.scmPr', 'scmCsMaterials.scmPrLine', 'scmWarehouse');
        $data = $materialCs->scmCsMaterials->groupBy(['scm_material_id'])->values()->all();
        data_forget($materialCs, 'scmCsMaterials');

        $materialCs['scmCsMaterials'] = $data;

        try {
            return response()->success('Detail data', $materialCs, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Retrieves the data for a specific CS ID.
     *
     * @param int $csId The id of the cs.
     * @throws Some_Exception_Class If the csId is not found.
     * @return JsonResponse
     */
    public function getCsData($csId)
    {
        $scmCs = ScmCs::query()
            ->with('scmCsMaterials.scmMaterial', 'scmCsMaterials.scmPr', 'scmWarehouse')
            ->find($csId);

        $scmCs['scmCsVendor'] = ScmCsVendor::query()
            ->with('scmVendor')
            ->where('scm_cs_id', $csId)
            ->get()
            ->groupBy('scm_vendor_id');

        $scmCs['scmCsMaterialVendor'] = ScmCsMaterialVendor::query()
            ->with('scmCsMaterial.scmMaterial', 'scmCsMaterial.scmPr')
            ->where('scm_cs_id', $csId)
            ->get()
            ->groupBy(['scm_material_id', 'scm_pr_id', 'scm_vendor_id']);

        $scmCs['latestPoItem'] = ScmPoItem::query()
            ->with(['scmPoLine.scmPo'])
            ->whereIn('scm_material_id', $scmCs->scmCsMaterials->pluck('scm_material_id')->toArray())
            ->get()
            ->sortByDesc(function ($item) {
                return $item->scmPoLine->scmPo->date;
            })
            ->groupBy('scm_material_id')
            ->map(function ($items) {
                return $items[0];
            });

        $scmCs['scmCsMaterial'] = ScmCsMaterial::query()
            ->with('scmMaterial', 'scmPr')
            ->where('scm_cs_id', $csId)
            ->get()
            ->groupBy(['scm_material_id', 'scm_pr_id'])
            ->map(function ($items) {
                return $items->map(function ($data) {
                    $data[0]['sum_quantity'] = $data->sum('quantity');

                    return $data;
                });
            });

        return response()->success('Detail data', $scmCs, 200);
    }

    public function selectedSupplierstore(SupplierSelectionRequest $request)
    {

        $data = $request->only('id', 'selection_ground', 'technical_acceptance', 'auditor_remarks_date', 'auditor_remarks', 'scmCsVendor');

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
                $csVendor->update(['is_selected' => $value[0]['is_selected'], 'technical_acceptance' => $value[0]['technical_acceptance']]);
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
        $cs = [];

        if (isset($request->searchParam)) {
            $cs = ScmCs::query()
                ->with('scmCsVendors', 'scmCsMaterials', 'scmCsMaterialVendors', 'selectedVendors')
                ->has('selectedVendors')
                ->whereHas('scmCsMaterials.scmPr', function ($query) use ($request) {
                    $query->where(function ($query) use ($request) {
                        $query->where('ref_no', 'like', '%' . $request->searchParam . '%')
                            ->where('business_unit', $request->business_unit)
                            ->where('scm_warehouse_id', $request->scm_warehouse_id)
                            ->where('purchase_center', $request->purchase_center);
                    });
                })
                ->whereDate('expire_date', '>=', date('Y-m-d'))
                ->doesntHave('scmPo')
                ->filter(function($item){
                    return $item->scmCsMaterials->filter(function($material){
                        return $material->quantity > $material->scmPoItems->sum('quantity');
                    })->isNotEmpty();
                })
                ->orderByDesc('ref_no')
                ->get();
        } elseif (isset($request->scm_warehouse_id) && isset($request->purchase_center)) {
            $cs = ScmCs::query()
                ->with('scmCsVendors', 'scmCsMaterials', 'scmCsMaterialVendors', 'selectedVendors')
                ->has('selectedVendors')
                ->whereHas('scmCsMaterials.scmPr', function ($query) use ($request) {
                    $query->where(function ($query) use ($request) {
                        $query->where('business_unit', $request->business_unit)
                            ->where('scm_warehouse_id', $request->scm_warehouse_id)
                            ->where('purchase_center', $request->purchase_center);
                    });
                })
                ->whereDate('expire_date', '>=', date('Y-m-d'))
                ->orderByDesc('ref_no')
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

    public function selectedVendors(): JsonResponse
    {
        $csVendor = ScmCs::query()
            ->with('selectedVendors.scmVendor', 'selectedVendors.scmCsLandedCost', 'selectedVendors.scmCsPaymentInfo')
            ->find(request()->scm_cs_id);

        return response()->success('Search result', $csVendor, 200);
    }

    public function storeCsLandedCost(CsLandedCostRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            foreach ($request->selectedVendors as $vendor) {
                $data = ScmCsLandedCost::create($vendor['scmCsLandedCost']);
                ScmCsPaymentInfo::create($vendor['scmCsPaymentInfo']);
            }

            DB::commit();

            return response()->success('Data created succesfully', null, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    public function updateCsLandedCost(CsLandedCostRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            foreach ($request->selectedVendors as $vendor) {
                if (isset($vendor['scm_cs_id'])) {
                    $scmCsLandedCost = ScmCsLandedCost::where('scm_cs_id', $request->scm_cs_id)
                        ->where('scm_vendor_id', $vendor['scm_vendor_id'])
                        ->first();

                    $scmCsLandedCost->update($vendor['scmCsLandedCost']);

                    $scmCsPaymentInfo = ScmCsPaymentInfo::where('scm_cs_id', $request->scm_cs_id)
                        ->where('scm_vendor_id', $vendor['scm_vendor_id'])
                        ->first();

                    $scmCsPaymentInfo->update($vendor['scmCsPaymentInfo']);
                } else {
                    ScmCsLandedCost::create($vendor['scmCsLandedCost']);
                    ScmCsPaymentInfo::create($vendor['scmCsPaymentInfo']);
                }
            }

            DB::commit();

            return response()->success('Data updated succesfully', null, 202);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }
}
