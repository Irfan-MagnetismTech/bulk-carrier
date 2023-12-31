<?php

namespace Modules\SupplyChain\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Services\CurrentStock;
use Modules\SupplyChain\Entities\ScmAdjustment;
use Modules\SupplyChain\Entities\ScmStockLedger;
use Modules\SupplyChain\Services\StockLedgerData;
use Modules\SupplyChain\Http\Requests\ScmAdjustmentRequest;

class ScmAdjustmentController extends Controller
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
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $datas = ScmAdjustment::with(
                'scmWarehouse',
                'createdBy',
                'scmAdjustmentLines.scmMaterial',
            )->latest()->paginate(10);

            return response()->success('Data list', $datas, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @return JsonResponse
     */
    public function store(ScmAdjustmentRequest $request): JsonResponse
    {
        $requestData = $request->except('ref_no', 'adjustment_composite_key');

        $requestData['ref_no'] = UniqueId::generate(ScmAdjustment::class, 'AJT');

        try {
            DB::beginTransaction();

            $adjustment = ScmAdjustment::create($requestData);

            $linesData = CompositeKey::generateArrayWithCompositeKey($request->scmAdjustmentLines, $adjustment->id, 'scm_material_id', 'ajt');
            $adjustment->scmAdjustmentLines()->createMany($linesData);

            if ($request->type === 'Addition') {
                (new StockLedgerData)->insert($adjustment, $linesData);
            } else {
                $dataForStock = [];
                foreach ($request->scmAdjustmentLines as $key => $value) {
                    $dataForStock[] = (new StockLedgerData)->out($value['scm_material_id'], $requestData['scm_warehouse_id'], $value['quantity'], 'lifo');
                }
                $dataForStockLedger = array_merge(...$dataForStock);

                $adjustment->stockable()->createMany($dataForStockLedger);
            }

            DB::commit();

            return response()->success('Data created succesfully', $adjustment, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * 
     * @param ScmAdjustment $movementRequisition
     * @return JsonResponse
     */
    public function show(ScmAdjustment $adjustment): JsonResponse
    {
        try {
            $adjustment->load('scmAdjustmentLines.scmMaterial', 'scmWarehouse', 'createdBy');
            return response()->success('data', $adjustment, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param ScmAdjustmentRequest $request
     * @param ScmAdjustment $movementRequisition
     * @return JsonResponse
     */
    public function update(ScmAdjustmentRequest $request, ScmAdjustment $adjustment): JsonResponse
    {
        try {
            DB::beginTransaction();
            $adjustment->update($request->all());

            $adjustment->scmAdjustmentLines()->delete();

            $adjustment->stockable()->delete();

            $linesData = CompositeKey::generateArrayWithCompositeKey($request->scmAdjustmentLines, $adjustment->id, 'scm_material_id', 'ajt');

            $adjustment->scmAdjustmentLines()->createMany($linesData);

            if ($request->type === 'Addition') {
                (new StockLedgerData)->insert($adjustment, $linesData);
            } else {
                $dataForStock = [];
                foreach ($request->scmAdjustmentLines as $key => $value) {
                    $dataForStock[] = (new StockLedgerData)->out($value['scm_material_id'], $request->scm_warehouse_id, $value['quantity'], 'lifo');
                }
                $dataForStockLedger = array_merge(...$dataForStock);

                $adjustment->stockable()->createMany($dataForStockLedger);
            }

            return response()->success('Data updated sucessfully!', $adjustment, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param ScmAdjustment $movementRequisition
     * @return JsonResponse
     */
    public function destroy(ScmAdjustment $adjustment): JsonResponse
    {
        try {
            $adjustment->scmAdjustmentLines()->delete();
            $adjustment->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }
}
