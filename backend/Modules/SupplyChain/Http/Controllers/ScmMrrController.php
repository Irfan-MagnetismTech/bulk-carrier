<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmPo;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Entities\ScmMrr;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Entities\ScmPoLine;
use Modules\SupplyChain\Entities\ScmPrLine;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Services\CurrentStock;
use Modules\SupplyChain\Services\StockLedgerData;
use Modules\SupplyChain\Http\Requests\ScmMrrRequest;

class ScmMrrController extends Controller
{
    function __construct(private CompositeKey $compositeKey)
    {
        //     $this->middleware('permission:charterer-contract-create|charterer-contract-edit|charterer-contract-show|charterer-contract-delete', ['only' => ['index','show']]);
        //     $this->middleware('permission:charterer-contract-create', ['only' => ['store']]);
        //     $this->middleware('permission:charterer-contract-edit', ['only' => ['update']]);
        //     $this->middleware('permission:charterer-contract-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $mrr = ScmMrr::query()
                ->with('scmMrrLines', 'scmPo', 'scmPr', 'scmWarehouse', 'scmLcRecord', 'createdBy', 'accCashRequisition')
                ->globalSearch(request()->all());

            return response()->success('Data list', $mrr, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmMrrRequest $request): JsonResponse
    {
        $requestData = $request->except('ref_no');
        $requestData['ref_no'] = UniqueId::generate(ScmMrr::class, 'MRR');

        try {
            DB::beginTransaction();

            $scmMrr = ScmMrr::create($requestData);
            $scmMrr->scmMrrLines()->createUpdateOrDelete($request->scmMrrLines);
            StockLedgerData::insert($scmMrr, $request->scmMrrLines);

            DB::commit();

            return response()->success('Data created succesfully', $scmMrr, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmMrr $materialReceiptReport
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $scmMrr = ScmMrr::query()
            ->with('scmMrrLines.scmMaterial', 'scmMrrLines.scmPoLine', 'scmMrrLines.scmPrLine', 'scmPo', 'scmPr', 'scmWarehouse', 'scmLcRecord', 'createdBy', 'accCashRequisition')
            ->find($id);

        $scmMrrLines = $scmMrr->scmMrrLines->map(function ($scmMrrLine) use ($scmMrr) {
            $lines = [
                'scm_material_id' => $scmMrrLine->scm_material_id,
                'scmMaterial' => $scmMrrLine->scmMaterial,
                'unit' => $scmMrrLine->unit,
                'brand' => $scmMrrLine->brand,
                'model' => $scmMrrLine->model,
                'quantity' => $scmMrrLine->quantity,
                'rate' => $scmMrrLine->rate,
                'net_rate' => $scmMrrLine->net_rate,
                'po_qty' => $scmMrrLine?->scmPoLine?->quantity ?? 0,
                'pr_qty' => $scmMrrLine?->scmPrLine?->quantity ?? 0,
                'current_stock' => CurrentStock::count($scmMrrLine->scm_material_id, $scmMrr->scm_warehouse_id),
                'po_composite_key' => $scmMrrLine->po_composite_key ?? null,
                'pr_composite_key' => $scmMrrLine->pr_composite_key ?? null,
                'max_quantity' => ($scmMrrLine->po_composite_key != null && $scmMrrLine->po_composite_key != '') ? ($scmMrrLine->scmPoLine->quantity - $scmMrrLine->scmPoLine->scmMrrLines->sum('quantity') + $scmMrrLine->quantity) : ($scmMrrLine->scmPrLine->quantity - $scmMrrLine->scmPrLine->scmMrrLines->sum('quantity') + $scmMrrLine->quantity),
                // 'max_quantity' => $scmMrrLine->scmPoLine->scmMrrLines->sum('quantity'),
            ];

            return $lines;
        });
        data_forget($scmMrr, 'scmMrrLines');
        $scmMrr->scmMrrLines = $scmMrrLines;

        try {
            return response()->success('data', $scmMrr, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmMrrRequest $request
     * @param ScmMrr $materialReceiptReport
     * @return JsonResponse
     */
    public function update(ScmMrrRequest $request, ScmMrr $materialReceiptReport): JsonResponse
    {
        try {
            DB::beginTransaction();

            $materialReceiptReport->update($request->all());
            $materialReceiptReport->scmMrrLines()->createUpdateOrDelete($request->scmMrrLines);

            $materialReceiptReport->stockable()->delete();
            StockLedgerData::insert($materialReceiptReport, $request->scmMrrLines);

            DB::commit();

            return response()->success('Data updated sucessfully!', $materialReceiptReport, 202);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmMrr $materialReceiptReport
     * @return JsonResponse
     */
    public function destroy(ScmMrr $materialReceiptReport): JsonResponse
    {
        try {
            $materialReceiptReport->scmMrrLines()->delete();
            $materialReceiptReport->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchMrr(Request $request): JsonResponse
    {
        if ($request->has('searchParam')) {
            $materialReceiptReport = ScmMrr::query()
                ->with('scmMrrLines.scmMaterial.account')
                ->where(function ($query) use ($request) {
                    $query->where('ref_no', 'like', '%' . $request->searchParam . '%')
                        ->where('business_unit', $request->business_unit)
                        ->where('acc_cost_center_id', $request->cost_center_id);
                })
                ->orderByDesc('ref_no')
                ->limit(10)
                ->get();
        } else {
            $materialReceiptReport = ScmMrr::query()
                ->with('scmMrrLines.scmMaterial')
                ->where(function ($query) use ($request) {
                    $query->where('business_unit', $request->business_unit)
                        ->where('acc_cost_center_id', $request->cost_center_id);
                })
                ->orderByDesc('ref_no')
                ->limit(10)
                ->get();
        }

        $materialReceiptReport = $materialReceiptReport->map(function ($item) {
            $item->scmMaterials = $item->scmMrrLines->map(function ($item1) {
                return $item1->scmMaterial;
            });
            return $item;
        });

        return response()->success('Search Result', $materialReceiptReport, 200);
    }

    /**
     * Retrieves Po or Pr Wise Mrr Data.
     *
     * @param Request $request
     * @throws \Exception
     * @return JsonResponse
     */
    public function getPoOrPrWiseMrrData(Request $request): JsonResponse
    {
        try {
            if ($request->pr_id) {
                $scmPr = ScmPr::query()
                    ->with([
                        'scmWarehouse',
                        'scmPrLines.scmMaterial',
                    ])
                    ->where('id', $request->pr_id)
                    ->first();

                $data = [
                    'type' => 'CASH',
                    'scmWarehouse' => $scmPr->scmWarehouse,
                    'scm_warehouse_id' => $scmPr->scm_warehouse_id,
                    'acc_cost_center_id' => $scmPr->acc_cost_center_id,
                    'scm_pr_no' => $scmPr->ref_no,
                    'scm_pr_id' => $scmPr->id,
                    'scmPr' => $scmPr,
                    'pr_date' => $scmPr->raised_date,
                    'business_unit' => $scmPr->business_unit,
                    'purchase_center' => $scmPr->purchase_center,
                    'scmMrrLines' => $scmPr->scmPrLines->map(function ($item) use ($scmPr) {
                        return [
                            'scmMaterial' => $item->scmMaterial,
                            'current_stock' => CurrentStock::count($item->scmMaterial->id, $scmPr->scm_warehouse_id),
                            'scm_material_id' => $item->scmMaterial->id,
                            'unit' => $item->scmMaterial->unit,
                            'brand' => $item->brand,
                            'model' => $item->model,
                            'quantity' => $item->quantity,
                            'pr_qty' => $item->quantity,
                            'pr_composite_key' => $item->pr_composite_key,
                            'max_quantity' => $item->quantity,
                        ];
                    })
                ];
            } else if ($request->po_id) {
                $scmPo = ScmPo::query()
                    ->with([
                        'scmWarehouse',
                        'scmPoLines.scmMaterial',
                        'scmPoLines.scmPrLine',
                    ])
                    ->where('id', $request->po_id)
                    ->first();

                $data = [
                    'type' => strtoupper($scmPo->purchase_center),
                    'scmWarehouse' => $scmPo->scmWarehouse,
                    'scm_warehouse_id' => $scmPo->scm_warehouse_id,
                    'acc_cost_center_id' => $scmPo->acc_cost_center_id,
                    'scm_po_no' => $scmPo->ref_no,
                    'scm_po_id' => $scmPo->id,
                    'scmPo' => $scmPo,
                    'scmPr' => $scmPo->scmPr,
                    'scm_pr_no' => $scmPo->scmPr->ref_no,
                    'scm_pr_id' => $scmPo->scmPr->id,
                    // 'scmCs' => $scmPo?->scmCs ?? null,
                    // 'scm_cs_id' => $scmPo?->scm_cs_id ?? '',
                    // 'scm_cs_no' => $scmPo?->scmCs?->ref_no ?? '',
                    'po_date' => $scmPo->date,
                    'business_unit' => $scmPo->business_unit,
                    'purchase_center' => $scmPo->purchase_center,
                    'scmMrrLines' => $scmPo->scmPoLines->map(function ($item) use ($scmPo) {
                        return [
                            'scmMaterial' => $item->scmMaterial,
                            'scm_material_id' => $item->scmMaterial->id,
                            'current_stock' => CurrentStock::count($item->scmMaterial->id, $scmPo->scm_warehouse_id),
                            'unit' => $item->scmMaterial->unit,
                            'brand' => $item->brand,
                            'model' => $item->model,
                            'quantity' => $item->quantity,
                            'po_qty' => $item->quantity,
                            'pr_qty' => $item->scmPrLine->quantity,
                            'rate' => $item->rate,
                            'net_rate' => $item->net_rate,
                            'po_composite_key' => $item->po_composite_key,
                            'pr_composite_key' => $item->pr_composite_key,
                            'max_quantity' => $item->quantity,
                        ];
                    })
                ];
            } else {
                $data = [$request->all()];
            }
            return response()->success('data', $data, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Retrieves the materials associated with a given purchase requisition ID.
     *
     * @return JsonResponse
     */
    public function getMaterialByPrId(): JsonResponse
    {
        if (!request()->scm_po_id) {
            $prMaterials = ScmPrLine::query()
                ->with('scmMaterial', 'scmMrrLines')
                ->where('scm_pr_id', request()->scm_pr_id)
                ->get()
                ->map(function ($item) {
                    $data = $item->scmMaterial;
                    $data['brand'] = $item->brand;
                    $data['model'] = $item->model;
                    $data['unit'] = $item->unit;
                    $data['quantity'] = $item->quantity;
                    $data['pr_qty'] = $item->quantity;
                    $data['po_qty'] = 0;
                    $data['rate'] = 0;
                    $data['net_rate'] = 0;
                    $data['pr_composite_key'] = $item->pr_composite_key;
                    $data['po_composite_key'] = null;
                    $data['current_stock'] = CurrentStock::count($item->scmMaterial->id, request()->scm_warehouse_id);
                    if (request()->scm_mrr_id) {
                        $data['mrr_quantity'] = $item->scmMrrLines->where('scm_mrr_id', request()->scm_mrr_id)->where('pr_composite_key', $item->pr_composite_key)->first()->quantity;
                    } else {
                        $data['mrr_quantity'] = 0;
                    }
                    $data['max_quantity'] = $item->quantity - $item->scmMrrLines->sum('quantity') + $data['mrr_quantity'];

                    return $data;
                });
            return response()->success('data list', $prMaterials, 200);
        }

        if (request()->scm_po_id) {
            $prMaterials = ScmPoLine::query()
                ->with('scmMaterial', 'scmMrrLines', 'scmPrLine')
                ->where('scm_po_id', request()->scm_po_id)
                ->get()
                ->map(function ($item) {
                    $data = $item->scmMaterial;
                    $data['brand'] = $item->brand;
                    $data['model'] = $item->model;
                    $data['unit'] = $item->unit;
                    $data['quantity'] = $item->quantity;
                    if (request()->scm_mrr_id) {
                        $data['mrr_quantity'] = $item->scmMrrLines->where('scm_mrr_id', request()->scm_mrr_id)->where('po_composite_key', $item->po_composite_key)->quantity;
                    } else {
                        $data['mrr_quantity'] = 0;
                    }
                    $data['max_quantity'] = $item->quantity - $item->scmMrrLines->sum('quantity') + $data['mrr_quantity'];
                    $data['po_qty'] = $item->quantity;
                    $data['pr_qty'] = $item->scmPrLine->quantity;
                    $data['rate'] = $item->rate;
                    $data['net_rate'] = $item->net_rate;
                    $data['pr_composite_key'] = $item->pr_composite_key;
                    $data['po_composite_key'] = $item->po_composite_key;
                    $data['current_stock'] = CurrentStock::count($item->scmMaterial->id, request()->scm_warehouse_id);
                    // $data['max_quantity'] = $item->quantity - $item->scmMrrLines->sum('quantity');//some edit needed
                    return $data;
                });
            return response()->success('data list', $prMaterials, 200);
        }
    }
}
