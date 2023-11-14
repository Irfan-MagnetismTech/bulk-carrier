<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmSi;
use Modules\SupplyChain\Entities\ScmSir;
use Modules\SupplyChain\Entities\ScmSr;
use Modules\SupplyChain\Entities\ScmStockLedger;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Services\CurrentStock;
use Modules\SupplyChain\Http\Requests\ScmSiRequest;
use Modules\SupplyChain\Http\Requests\ScmSirRequest;

class ScmSirController extends Controller
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
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $storeIssuereturns = ScmSir::with('scmSirLines.scmMaterial', 'scmWarehouse', 'createdBy')->latest()->paginate(10);

            return response()->success('Data list', $storeIssuereturns, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmSirRequest $request): JsonResponse
    {
        $requestData = $request->except('ref_no', 'sr_composite_key');

        $requestData['ref_no'] = $this->uniqueId->generate(ScmSir::class, 'SIR');

        $dataForStockLedger = [];

        foreach ($requestData['scmSirLines'] as $key => $value) {

            $stockLedgerData = ScmStockLedger::query()
                ->where([
                    'stockable_id' => $requestData['scmSi']['id'],
                    'scm_warehouse_id' => $requestData['scm_warehouse_id'],
                    'stockable_type' => ScmSi::class,
                    'scm_material_id' => $value['scm_material_id']
                ])
                ->orderByDesc('id')
                ->get();

            $remainingQty = $value['quantity'];

            foreach ($stockLedgerData as $key => $stock) {

                $convertMinusToPlus = $stock->quantity < 0 ? $stock->quantity * -1 : $stock->quantity;
                if ($convertMinusToPlus >= $remainingQty) {
                    $dataForStockLedger[] = [
                        'scm_material_id' => $stock->scm_material_id,
                        'scm_warehouse_id' => $stock->scm_warehouse_id,
                        'acc_cost_center_id' => $stock->acc_cost_center_id,
                        'parent_id' => $stock->parent_id,
                        'quantity' => $remainingQty,
                        'recievable_type' => $stock->recievable_type,
                        'recievable_id' => $stock->recievable_id,
                        'gross_unit_price' => $stock->gross_unit_price,
                        'gross_foreign_unit_price' => $stock->gross_foreign_unit_price,
                        'net_unit_price' => $stock->net_unit_price,
                        'net_foreign_unit_price' => $stock->net_foreign_unit_price,
                        'currency' => $stock->currency,
                        'exchange_rate' => $stock->exchange_rate,
                        'business_unit' => $stock->business_unit,
                        'received_date' => $stock->received_date,
                    ];
                    break;
                } else {
                    $dataForStockLedger[] = [
                        'scm_material_id' => $stock->scm_material_id,
                        'scm_warehouse_id' => $stock->scm_warehouse_id,
                        'acc_cost_center_id' => $stock->acc_cost_center_id,
                        'parent_id' => $stock->parent_id,
                        'quantity' => $convertMinusToPlus,
                        'recievable_type' => $stock->recievable_type,
                        'recievable_id' => $stock->recievable_id,
                        'gross_unit_price' => $stock->gross_unit_price,
                        'gross_foreign_unit_price' => $stock->gross_foreign_unit_price,
                        'net_unit_price' => $stock->net_unit_price,
                        'net_foreign_unit_price' => $stock->net_foreign_unit_price,
                        'currency' => $stock->currency,
                        'exchange_rate' => $stock->exchange_rate,
                        'business_unit' => $stock->business_unit,
                        'received_date' => $stock->received_date,
                    ];
                    $remainingQty = $remainingQty - $convertMinusToPlus;
                }
            }
        }

        try {
            DB::beginTransaction();

            $scmSir = ScmSir::create($requestData);
            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmSirLines, $scmSir->id, 'scm_material_id', 'sir');
            $scmSir->scmSirLines()->createMany($linesData);
            $scmSir->stockable()->createMany($dataForStockLedger);

            DB::commit();

            return response()->success('Data created succesfully', $scmSir, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmSi $storeIssue
     * @return JsonResponse
     */
    public function show(ScmSir $storeIssueReturn): JsonResponse
    {

        try {
            $storeIssueReturn->load('scmSirLines.scmMaterial', 'scmWarehouse', 'createdBy', 'scmSi');

            $scmSirLines = $storeIssueReturn->scmSirLines->map(function ($scmSirLine) use ($storeIssueReturn) {
                $lines = [
                    'scm_material_id' => $scmSirLine->scm_material_id,
                    'scmMaterial' => $scmSirLine->scmMaterial,
                    'unit' => $scmSirLine->unit,
                    'quantity' => $scmSirLine->quantity,
                    // 'sr_quantity' => $scmSirLine->scmSrLine->quantity,
                    // 'current_stock' => (new CurrentStock)->count($scmSirLine->scm_material_id, $storeIssue->scm_warehouse_id),
                    'sr_composite_key' => $scmSirLine->sr_composite_key ?? null,
                    'si_composite_key' => $scmSirLine->si_composite_key ?? null,
                ];

                return $lines;
            });

            data_forget($storeIssueReturn, 'scmSirLines');
            
            $storeIssueReturn->scmSirLines = $scmSirLines;

            return response()->success('Data updated sucessfully!', $storeIssueReturn, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmSiRequest $request
     * @param ScmSi $storeIssue
     * @return JsonResponse
     */
    public function update(ScmSirRequest $request, ScmSir $storeIssueReturn): JsonResponse
    {
        $requestData = $request->except('ref_no', 'sr_composite_key');

        try {
            $storeIssueReturn->update($requestData);

            $storeIssueReturn->scmSirLines()->delete();

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmSirLines, $storeIssueReturn->id, 'scm_material_id', 'sir');

            $storeIssueReturn->scmSirLines()->createMany($linesData);

            return response()->success('Data updated sucessfully!', $storeIssueReturn, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmSi $storeIssue
     * @return JsonResponse
     */
    public function destroy(ScmSir $storeIssueReturn): JsonResponse
    {
        try {
            $storeIssueReturn->scmSirLines()->delete();
            $storeIssueReturn->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function getSiWiseData(Request $request): JsonResponse
    {
        if ($request->si_id != null) {
            $scmSi = ScmSi::query()
                ->with([
                    'scmWarehouse',
                    'scmSiLines.scmMaterial',
                ])
                ->where('id', $request->si_id)
                ->first();

            $data = [
                'scmSirLines' => $scmSi->scmSiLines->map(function ($item) {
                    return [
                        'scmMaterial' => $item->scmMaterial,
                        'scm_material_id' => $item->scmMaterial->id,
                        'unit' => $item->scmMaterial->unit,
                        'quantity' => $item->quantity,
                        'notes' => '',
                        'si_quantity' => $item->quantity,
                        'sr_composite_key' => $item->sr_composite_key,
                        'si_composite_key' => $item->si_composite_key,
                        // 'rate' => $item->rate,
                        // 'total_price' => $item->total_price
                    ];
                })
            ];
        } else {
            // $scmCs = ScmCs::query()
            // ->with('scmWarehouse', 'scmSr')
            // ->where([['id', $request->cs_id], ['scm_pr_id', $request->pr_id]])
            // ->get();
        }

        return response()->success('data', $data, 200);
    }
}
