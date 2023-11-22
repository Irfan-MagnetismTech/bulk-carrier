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
            )->latest()->paginate(10);

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

            $ScmMo = ScmMo::create($requestData);

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmMoLines, $ScmMo->id, 'scm_material_id', 'mo');
            

            $ScmMo->scmMoLines()->createMany($linesData);

            //loop through each line and update current stock
            $dataForStock = [];

            foreach ($request->scmMoLines as $scmMoLine) {
                $dataForStock[] = (new StockLedgerData)->out($scmMoLine['scm_material_id'], $ScmMo->scm_warehouse_id, $scmMoLine['quantity']);
            }

            $dataForStockLedger = array_merge(...$dataForStock);

            $ScmMo->stockable()->createMany($dataForStockLedger);

            DB::commit();

            return response()->success('Data created succesfully', $ScmMo, 201);
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

            $scmMoLines = $movementOut->scmMoLines->map(function ($scmMoLine) use ($movementOut) {
                $lines = [
                    'scm_material_id' => $scmMoLine->scm_material_id,
                    'scmMaterial' => $scmMoLine->scmMaterial,
                    'unit' => $scmMoLine->unit,
                    'quantity' => $scmMoLine->quantity,
                    'mmr_quantity' => $scmMoLine->scmMmrLine->quantity,
                    'max_quantity' => $scmMoLine->scmMmrLine->scmMoLines->sum('quantity') - $scmMoLine->quantity,
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
}
