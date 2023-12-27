<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmMo;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Services\CurrentStock;
use Modules\SupplyChain\Services\StockLedgerData;
use Modules\SupplyChain\Http\Requests\ScmMoRequest;

class ScmMoController extends Controller
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
            $movementOuts = ScmMo::with(
                'scmMoLines.scmMaterial',
                'fromWarehouse',
                'toWarehouse',
                'createdBy',
            )
            ->globalSearch(request()->all());

            return response()->success('Data list', $movementOuts, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScmMoRequest $request)
    {
        $requestData = $request->except('ref_no', 'mo_composite_key');

        $requestData['ref_no'] = $this->uniqueId->generate(ScmMo::class, 'MO');

        try {
            DB::beginTransaction();

            $scmMo = ScmMo::create($requestData);

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmMoLines, $scmMo->id, 'scm_material_id', 'mo');


            $scmMo->scmMoLines()->createMany($linesData);

            //loop through each line and update current stock
            $dataForStock = [];

            foreach ($request->scmMoLines as $scmMoLine) {
                $dataForStock[] = (new StockLedgerData)->out($scmMoLine['scm_material_id'], $scmMo->from_warehouse_id, $scmMoLine['quantity']);
            }

            $dataForStockLedger = array_merge(...$dataForStock);

            $scmMo->stockable()->createMany($dataForStockLedger);

            DB::commit();

            return response()->success('Data created succesfully', $scmMo, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmMo $movementOut
     * @return JsonResponse
     */
    public function show(ScmMo $movementOut): JsonResponse
    {
        try {
            $movementOut->load(
                'scmMoLines.scmMaterial',
                'fromWarehouse',
                'toWarehouse',
                'createdBy',
                'scmMmr',
            );

            // 'scmMaterial' => $item->scmMaterial,
            //                 'scm_material_id' => $item->scmMaterial->id,
            //                 'unit' => $item->scmMaterial->unit,
            //                 'quantity' => $item->quantity,
            //                 'mmr_quantity' => $item->quantity,
            //                 'mmr_composite_key' => $item->mmr_composite_key,
            //                 'current_stock' => (new CurrentStock)->count($item->scm_material_id, $scmMmr->from_warehouse_id),
            //                 'max_quantity' => $maxQty,
            //                 'remaining_quantity' => $remainingQty,
            $scmMoLines = $movementOut->scmMoLines->map(function ($scmMoLine) use ($movementOut) {
                $maxQty =  $scmMoLine->scmMmrLine->quantity + $scmMoLine->quantity - $scmMoLine->scmMmrLine->scmMoLines->sum('quantity');
                $currentStock = (new CurrentStock)->count($scmMoLine->scm_material_id, $movementOut->from_warehouse_id) + $scmMoLine->quantity;
                $maxQty = $currentStock > $maxQty ? $maxQty : $currentStock;
                $lines = [
                    'scm_material_id' => $scmMoLine->scm_material_id,
                    'scmMaterial' => $scmMoLine->scmMaterial,
                    'unit' => $scmMoLine->unit,
                    'quantity' => $scmMoLine->quantity,
                    'mmr_quantity' => $scmMoLine->scmMmrLine->quantity,
                    'max_quantity' => $maxQty,
                    'current_stock' => (new CurrentStock)->count($scmMoLine->scm_material_id, $movementOut->from_warehouse_id),
                    'remaining_quantity' => $scmMoLine->scmMmrLine->quantity - $scmMoLine->scmMmrLine->scmMoLines->sum('quantity'),
                    'mo_composite_key' => $scmMoLine->mo_composite_key ?? null,
                    'mmr_composite_key' => $scmMoLine->mmr_composite_key ?? null,

                ];

                return $lines;
            });

            data_forget($movementOut, 'scmMoLines');
            $movementOut->scmMoLines = $scmMoLines;

            return response()->success('Data updated sucessfully!', $movementOut, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmMoRequest $request
     * @param ScmMo $movementOut
     * @return JsonResponse
     */
    public function update(ScmMoRequest $request, ScmMo $movementOut): JsonResponse
    {
        $requestData = $request->except('ref_no', 'mo_composite_key');

        try {
            $movementOut->update($requestData);

            $movementOut->scmMoLines()->delete();

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmMoLines, $movementOut->id, 'scm_material_id', 'mo');

            $movementOut->scmMoLines()->createMany($linesData);

            return response()->success('Data updated sucessfully!', $movementOut, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmMo $movementOut
     * @return JsonResponse
     */
    public function destroy(ScmMo $movementOut): JsonResponse
    {
        try {
            $movementOut->scmMoLines()->delete();
            $movementOut->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }


      /**
     * Searches for MMR records based on the given request parameters.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchMo(Request $request): JsonResponse
    {
        try {
            if ($request->business_unit != 'ALL') {
                $movementRequisitions = ScmMo::query()
                    ->whereDoesntHave('scmMi')
                    ->with('scmMoLines', 'fromWarehouse', 'toWarehouse', 'createdBy')
                    ->whereBusinessUnit($request->business_unit)
                    ->when($request->searchParam, function ($query) {
                        return $query->where('ref_no', 'LIKE', "%".request()->searchParam."%");
                    })
                    ->when($request->mmr_id, function ($query) {
                        return $query->where('scm_mmr_id', request()->mmr_id);
                    })
                    // ->where('ref_no', 'LIKE', "%$request->searchParam%")
                    ->orderByDesc('ref_no')
                    // ->limit(10)
                    ->get();
            } else {
                $movementRequisitions = [];
            }

            return response()->success('Search result', $movementRequisitions, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }
}
