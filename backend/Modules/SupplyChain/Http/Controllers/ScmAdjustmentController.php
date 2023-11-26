<?php

namespace Modules\SupplyChain\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmAdjustment;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Services\CurrentStock;
use Modules\SupplyChain\Entities\ScmStockLedger;
use Modules\SupplyChain\Http\Requests\ScmAdjustmentRequest;

class ScmAdjustmentController extends Controller
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
            $movementRequisitions = ScmAdjustment::with(
                'fromWarehouse',
                'toWarehouse',
                'createdBy',
                'scmAdjustmentLines.scmMaterial',
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
    public function store(ScmAdjustmentRequest $request): JsonResponse
    {
        $requestData = $request->except('ref_no', 'mmr_composite_key');

        $requestData['ref_no'] = $this->uniqueId->generate(ScmAdjustment::class, 'MMR');

        try {
            DB::beginTransaction();

            $movementRequisition = ScmAdjustment::create($requestData);

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmAdjustmentLines, $movementRequisition->id, 'scm_material_id', 'mmr');

            $movementRequisition->scmAdjustmentLines()->createMany($linesData);

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
     * @param ScmAdjustment $movementRequisition
     * @return JsonResponse
     */
    public function show(ScmAdjustment $movementRequisition): JsonResponse
    {
        try {
            $movementRequisition->load('scmAdjustmentLines.scmMaterial', 'fromWarehouse', 'toWarehouse', 'createdBy',);

            $scmAdjustmentLines = $movementRequisition->scmAdjustmentLines->map(function ($scmAdjustmentLine) use ($movementRequisition) {
                $lines = [
                    'scm_material_id' => $scmAdjustmentLine->scm_material_id,
                    'scmMaterial' => $scmAdjustmentLine->scmMaterial,
                    'unit' => $scmAdjustmentLine->unit,
                    'present_stock' => (new CurrentStock)->count($scmAdjustmentLine->scm_material_id, $movementRequisition->to_warehouse_id),
                    'available_stock' => (new CurrentStock)->count($scmAdjustmentLine->scm_material_id, $movementRequisition->from_warehouse_id),
                    'quantity' => $scmAdjustmentLine->quantity,
                    'mmr_composite_key' => $scmAdjustmentLine->mmr_composite_key,
                ];

                return $lines;
            });

            data_forget($movementRequisition, 'scmAdjustmentLines');

            $movementRequisition->scmAdjustmentLines = $scmAdjustmentLines;
            return response()->success('data', $movementRequisition, 200);
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
    public function update(ScmAdjustmentRequest $request, ScmAdjustment $movementRequisition): JsonResponse
    {
        try {
            $movementRequisition->update($request->all());

            $movementRequisition->scmAdjustmentLines()->delete();

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmAdjustmentLines, $movementRequisition->id, 'scm_material_id', 'mmr');

            $movementRequisition->scmAdjustmentLines()->createMany($linesData);

            return response()->success('Data updated sucessfully!', $movementRequisition, 202);
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
    public function destroy(ScmAdjustment $movementRequisition): JsonResponse
    {
        try {
            $movementRequisition->scmAdjustmentLines()->delete();
            $movementRequisition->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }
}
