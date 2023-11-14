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
     * @param ScmMmr $movementRequisition
     * 
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
                    'specification' => $scmMmrLine->specification,
                    'present_stock' => (new CurrentStock)->count($scmMmrLine->scm_material_id, $movementRequisition->to_warehouse_id),
                    'available_stock' => (new CurrentStock)->count($scmMmrLine->scm_material_id, $movementRequisition->from_warehouse_id),
                    'quantity' => $scmMmrLine->quantity,
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

    public function searchVendor(Request $request): JsonResponse
    {
        $movementRequisitions = ScmMmr::query()
            ->with('scmMmrLines')
            ->where('name', 'like', "%$request->searchParam%")
            ->orderByDesc('name')
            ->limit(10)
            ->get();

        return response()->success('Search result', $movementRequisitions, 200);
    }
    public function getCurrentStockByWarehouse(): JsonResponse
    {
        $stockData = [
            'from_warehouse_stock' => (new CurrentStock)->count(request()->scm_material_id, request()->from_warehouse_id),
            'to_warehouse_stock' => (new CurrentStock)->count(request()->scm_material_id, request()->to_warehouse_id)
        ];

        return response()->success('Search result', $stockData, 200);
    }
}
