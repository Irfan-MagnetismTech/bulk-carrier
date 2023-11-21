<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmMmr;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Services\CurrentStock;
use Modules\SupplyChain\Entities\ScmStockLedger;
use Modules\SupplyChain\Http\Requests\ScmMmrRequest;

class ScmMmrController extends Controller
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
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $movementRequisitions = ScmMmr::with(
                'fromWarehouse',
                'toWarehouse',
                'createdBy',
                'scmMmrLines.scmMaterial',
            )->latest()->paginate(10);

            return response()->success('Data list', $movementRequisitions, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @return JsonResponse
     */
    public function store(ScmMmrRequest $request): JsonResponse
    {
        $requestData = $request->except('ref_no', 'mmr_composite_key');

        $requestData['ref_no'] = $this->uniqueId->generate(ScmMmr::class, 'MMR');

        try {
            DB::beginTransaction();

            $movementRequisition = ScmMmr::create($requestData);

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmMmrLines, $movementRequisition->id, 'scm_material_id', 'mmr');

            $movementRequisition->scmMmrLines()->createMany($linesData);

            DB::commit();

            return response()->success('Data created succesfully', $movementRequisition, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * 
     * @param ScmMmr $movementRequisition
     * @return JsonResponse
     */
    public function show(ScmMmr $movementRequisition): JsonResponse
    {
        try {
            $movementRequisition->load('scmMmrLines.scmMaterial', 'fromWarehouse', 'toWarehouse', 'createdBy',);

            $scmMmrLines = $movementRequisition->scmMmrLines->map(function ($scmMmrLine) use ($movementRequisition) {
                $lines = [
                    'scm_material_id' => $scmMmrLine->scm_material_id,
                    'scmMaterial' => $scmMmrLine->scmMaterial,
                    'unit' => $scmMmrLine->unit,
                    'present_stock' => (new CurrentStock)->count($scmMmrLine->scm_material_id, $movementRequisition->to_warehouse_id),
                    'available_stock' => (new CurrentStock)->count($scmMmrLine->scm_material_id, $movementRequisition->from_warehouse_id),
                    'quantity' => $scmMmrLine->quantity,
                    'mmr_composite_key' => $scmMmrLine->mmr_composite_key,
                ];

                return $lines;
            });

            data_forget($movementRequisition, 'scmMmrLines');

            $movementRequisition->scmMmrLines = $scmMmrLines;
            return response()->success('data', $movementRequisition, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param ScmMmrRequest $request
     * @param ScmMmr $movementRequisition
     * @return JsonResponse
     */
    public function update(ScmMmrRequest $request, ScmMmr $movementRequisition): JsonResponse
    {
        try {
            $movementRequisition->update($request->all());

            $movementRequisition->scmMmrLines()->delete();

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmMmrLines, $movementRequisition->id, 'scm_material_id', 'mmr');

            $movementRequisition->scmMmrLines()->createMany($linesData);

            return response()->success('Data updated sucessfully!', $movementRequisition, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param ScmMmr $movementRequisition
     * @return JsonResponse
     */
    public function destroy(ScmMmr $movementRequisition): JsonResponse
    {
        try {
            $movementRequisition->scmMmrLines()->delete();
            $movementRequisition->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }
    
    /**
     * Retrieves the current stock data for a given warehouse.
     *
     * @return JsonResponse
     */
    public function getCurrentStockByWarehouse(): JsonResponse
    {
        $stockData = [
            'from_warehouse_stock' => (new CurrentStock)->count(request()->scm_material_id, request()->from_warehouse_id),
            'to_warehouse_stock' => (new CurrentStock)->count(request()->scm_material_id, request()->to_warehouse_id)
        ];

        return response()->success('Search result', $stockData, 200);
    }

    /**
     * Searches for MMR records based on the given request parameters.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchMmr(Request $request): JsonResponse
    {
        try {
            if ($request->business_unit != 'ALL') {
                $movementRequisitions = ScmMmr::query()
                    ->with('scmMmrLines', 'fromWarehouse', 'toWarehouse', 'createdBy')
                    ->whereBusinessUnit($request->business_unit)
                    ->where('ref_no', 'LIKE', "%$request->searchParam%")
                    ->orderByDesc('ref_no')
                    ->limit(10)
                    ->get();
            } else {
                $movementRequisitions = [];
            }

            return response()->success('Search result', $movementRequisitions, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function getMmrWiseData(Request $request): JsonResponse
    {
        try {
            if ($request->mmr_id != null) {
                $scmMmr = ScmMmr::query()
                    ->with('scmMmrLines', 'fromWarehouse', 'toWarehouse', 'createdBy')
                    ->where('id', $request->mmr_id)
                    ->first();

                $data = [
                    'scmWarehouse' => $scmMmr->scmWarehouse,
                    'scm_warehouse_id' => $scmMmr->scm_warehouse_id,
                    'scm_department_id' => $scmMmr->scm_department_id,
                    'scm_mmr_id' => $scmMmr->id,
                    'scmMmr' => $scmMmr,
                    'acc_cost_center_id' => $scmMmr->acc_cost_center_id,
                    'business_unit' => $scmMmr->business_unit,
                    'scmMmrLines' => $scmMmr->scmMmrLines->map(function ($item) use ($scmMmr) {
                        return [
                            'scmMaterial' => $item->scmMaterial,
                            'scm_material_id' => $item->scmMaterial->id,
                            'unit' => $item->scmMaterial->unit,
                            'quantity' => $item->quantity,
                            'mmr_quantity' => $item->quantity,
                            'mmr_composite_key' => $item->mmr_composite_key,
                            'current_stock' => (new CurrentStock)->count($item->scm_material_id, $scmMmr->scm_warehouse_id),
                            // 'max_quantity' => "$item->quantity - $item->scmSiLines->sum('quantity'),"
                            // 'rate' => $item->rate,
                            // 'total_price' => $item->total_price
                        ];
                    })
                ];
            } else {
                // $scmCs = ScmCs::query()
                // ->with('scmWarehouse', 'scmMmr')
                // ->where([['id', $request->cs_id], ['scm_pr_id', $request->pr_id]])
                // ->get();
            }

            return response()->success('data', $data, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }
}