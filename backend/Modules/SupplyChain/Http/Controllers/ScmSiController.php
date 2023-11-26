<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmSi;
use Modules\SupplyChain\Entities\ScmSr;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Services\CurrentStock;
use Modules\SupplyChain\Services\StockLedgerData;
use Modules\SupplyChain\Http\Requests\ScmSiRequest;
use Illuminate\Support\Arr;
use Modules\SupplyChain\Entities\ScmStockLedger;

class ScmSiController extends Controller
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
            $storeIssues = ScmSi::with('scmSiLines.scmMaterial', 'scmWarehouse', 'createdBy')
            ->globalSearch(request()->all());


            return response()->success('Data list', $storeIssues, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScmSiRequest $request)
    {
        $requestData = $request->except('ref_no', 'sr_composite_key');

        $requestData['ref_no'] = $this->uniqueId->generate(ScmSi::class, 'SI');


        try {
            DB::beginTransaction();

            $scmSi = ScmSi::create($requestData);

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmSiLines, $scmSi->id, 'scm_material_id', 'si');

            $scmSi->scmSiLines()->createMany($linesData);

            //loop through each line and update current stock
            $dataForStock = [];
            //    collect($request->scmSiLines)->map(function ($scmSiLine) use ($scmSi, &$dataForStock) {
            //        $dataForStock[] = (new StockLedgerData)->out($scmSiLine->scm_material_id, $scmSi->scm_warehouse_id, $scmSiLine->quantity);
            //     });

            foreach ($request->scmSiLines as $scmSiLine) {
                $dataForStock[] = (new StockLedgerData)->out($scmSiLine['scm_material_id'], $scmSi->scm_warehouse_id, $scmSiLine['quantity']);
            }

            $dataForStockLedger = array_merge(...$dataForStock);

            $scmSi->stockable()->createMany($dataForStockLedger);

            DB::commit();

            return response()->success('Data created succesfully', $scmSi, 201);
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
    public function show(ScmSi $storeIssue): JsonResponse
    {
        try {
            $storeIssue->load('scmSiLines.scmMaterial', 'scmWarehouse', 'createdBy', 'scmSr');

            $scmSiLines = $storeIssue->scmSiLines->map(function ($scmSiLine) use ($storeIssue) {
                $lines = [
                    'scm_material_id' => $scmSiLine->scm_material_id,
                    'scmMaterial' => $scmSiLine->scmMaterial,
                    'unit' => $scmSiLine->unit,
                    'quantity' => $scmSiLine->quantity,
                    'sr_quantity' => $scmSiLine->scmSrLine->quantity,
                    'current_stock' => (new CurrentStock)->count($scmSiLine->scm_material_id, $storeIssue->scm_warehouse_id),
                    'max_quantity' => $scmSiLine->scmSrLine->scmSiLines->sum('quantity') - $scmSiLine->quantity,
                    'sr_composite_key' => $scmSiLine->sr_composite_key ?? null,
                ];

                return $lines;
            });
            data_forget($storeIssue, 'scmSiLines');
            $storeIssue->scmSiLines = $scmSiLines;

            return response()->success('Data updated sucessfully!', $storeIssue, 200);
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
    public function update(ScmSiRequest $request, ScmSi $storeIssue): JsonResponse
    {
        $requestData = $request->except('ref_no', 'sr_composite_key');

        try {
            $storeIssue->update($requestData);

            $storeIssue->scmSiLines()->delete();

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmSiLines, $storeIssue->id, 'scm_material_id', 'si');

            $storeIssue->scmSiLines()->createMany($linesData);

            return response()->success('Data updated sucessfully!', $storeIssue, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmSi $storeIssue
     * @return JsonResponse
     */
    public function destroy(ScmSi $storeIssue): JsonResponse
    {
        try {
            $storeIssue->scmSiLines()->delete();
            $storeIssue->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchStoreIssue(Request $request): JsonResponse
    {
        try {
            if ($request->business_unit != 'ALL') {
                $store_issue = ScmSi::query()
                    ->with('scmSiLines', 'scmWarehouse')
                    ->whereBusinessUnit($request->business_unit)
                    ->where('ref_no', 'LIKE', "%$request->searchParam%")
                    ->orderByDesc('ref_no')
                    ->limit(10)
                    ->get();
            } else {
                $purchase_requisition = [];
            }
            
            return response()->success('Search result', $store_issue, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function getSrWiseData(Request $request): JsonResponse
    {
        try {
            if ($request->sr_id != null) {
                $scmSr = ScmSr::query()
                    ->with([
                        'scmWarehouse',
                        'scmSrLines.scmMaterial',
                    ])
                    ->where('id', $request->sr_id)
                    ->first();

                

                $data = [
                    'scmWarehouse' => $scmSr->scmWarehouse,
                    'scm_warehouse_id' => $scmSr->scm_warehouse_id,
                    'scm_department_id' => $scmSr->scm_department_id,
                    'scm_sr_id' => $scmSr->id,
                    'scmSr' => $scmSr,
                    'sr_date' => $scmSr->date,
                    'acc_cost_center_id' => $scmSr->acc_cost_center_id,
                    'business_unit' => $scmSr->business_unit,
                    'scmSiLines' => $scmSr->scmSrLines->map(function ($item) use ($scmSr) {
                        return [
                            'scmMaterial' => $item->scmMaterial,
                            'scm_material_id' => $item->scmMaterial->id,
                            'unit' => $item->scmMaterial->unit,
                            'quantity' => $item->quantity,
                            'sr_quantity' => $item->quantity,
                            'sr_composite_key' => $item->sr_composite_key,
                            'current_stock' => (new CurrentStock)->count($item->scm_material_id, $scmSr->scm_warehouse_id),
                            'max_quantity' => $item->quantity - $item->scmSiLines->sum('quantity'),
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
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }
}
