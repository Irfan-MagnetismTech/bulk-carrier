<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmPo;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Entities\ScmMrr;
use Modules\SupplyChain\Http\Requests\ScmMrrRequest;

class ScmMrrController extends Controller
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
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $scmLcRecords = ScmMrr::query()
                ->with('scmMrrLines', 'scmVendor', 'scmWarehouse', 'scmPo')
                ->latest()
                ->when(request()->business_unit != "ALL", function ($query) {
                    $query->where('business_unit', request()->business_unit);
                })
                ->paginate(10);

            return response()->success('Data list', $scmLcRecords, 200);
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
        try {
            DB::beginTransaction();

            $scmMrr = ScmMrr::create($request->all());
            $scmMrr->scmMrrLines()->createUpdateOrDelete($request->scmMrrLines);
            DB::commit();

            return response()->success('Data created succesfully', $scmMrr, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmMrr $mrr
     * @return JsonResponse
     */
    public function show(ScmMrr $mrr): JsonResponse
    {
        try {
            return response()->success('data', $mrr->load('scmMrrLines', 'scmPo', 'scmPr', 'scmWarehouse', 'scmLcRecord', 'createdBy'), 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmMrrRequest $request
     * @param ScmMrr $mrr
     * @return JsonResponse
     */
    public function update(ScmMrrRequest $request, ScmMrr $mrr): JsonResponse
    {
        try {
            DB::beginTransaction();

            $mrr->update($request->all());
            $mrr->scmMrrLines()->createUpdateOrDelete($request->scmMrrLines);
            DB::commit();

            return response()->success('Data updated sucessfully!', $mrr, 202);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmMrr $mrr
     * @return JsonResponse
     */
    public function destroy(ScmMrr $mrr): JsonResponse
    {
        try {
            $mrr->scmMrrLines()->delete();
            $mrr->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchMrr(Request $request): JsonResponse
    {
        $mrr = ScmMrr::query()
            ->with('scmMrrLines')
            ->where('lc_no', 'like', "%$request->searchParam%")
            ->orderByDesc('lc_no')
            ->limit(10)
            ->get();

        return response()->success('Search result', $mrr, 200);
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
        if($request->pr_id)
        {
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
                'scmMrrLines' => $scmPr->scmPrLines->map(function ($item) {
                    return [
                        'scmMaterial' => $item->scmMaterial,
                        'scm_material_id' => $item->scmMaterial->id,
                        'unit' => $item->scmMaterial->unit,
                        'brand' => $item->brand,
                        'model' => $item->model,
                        'quantity' => $item->quantity,
                        'pr_qty' => $item->quantity,
                        'pr_composite_key' => $item->pr_composite_key,
                    ];
                })
            ];
        }
        else if($request->po_id)
        {
            $scmPo = ScmPo::query()
            ->with([
                'scmWarehouse',
                'scmPoLines.scmMaterial',
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
                // 'scmCs' => $scmPo?->scmCs ?? null,
                // 'scm_cs_id' => $scmPo?->scm_cs_id ?? '',
                // 'scm_cs_no' => $scmPo?->scmCs?->ref_no ?? '',
                'po_date' => $scmPo->raised_date,
                'business_unit' => $scmPo->business_unit,
                'purchase_center' => $scmPo->purchase_center,
                'scmMrrLines' => $scmPo->scmPoLines->map(function ($item) {
                    return [
                        'scmMaterial' => $item->scmMaterial,
                        'scm_material_id' => $item->scmMaterial->id,
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
                    ];
                })
            ];
        }
       
        else
        {
            $data = [$request->all()];
        }
        return response()->success('data', $data, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }
}
