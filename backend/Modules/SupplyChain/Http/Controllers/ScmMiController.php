<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmMi;
use Modules\SupplyChain\Entities\ScmMo;
use Modules\SupplyChain\Services\UniqueId;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Services\CurrentStock;
use Modules\SupplyChain\Services\StockLedgerData;
use Modules\SupplyChain\Http\Requests\ScmMiRequest;

class ScmMiController extends Controller
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
            $movementOuts = ScmMi::with(
                'scmMiLines.scmMaterial',
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
    public function store(ScmMiRequest $request)
    {
        $requestData = $request->except('ref_no', 'mi_composite_key');

        $requestData['ref_no'] = $this->uniqueId->generate(ScmMi::class, 'MI');


        try {
            DB::beginTransaction();

            $scmMi = ScmMi::create($requestData);

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmMiLines, $scmMi->id, 'scm_material_id', 'mi');

            $scmMi->scmMiLines()->createMany($linesData);

           $shortage = $scmMi->scmShortage()->create($request->scmMiShortage);
return response()->json($request->scmMiShortage,422);

            $shortage->scmMiShortageLines()->createMany($request['scmMiShortag']['scmMiShortageLines']);

        //    return response()->success('Data created succesfully', $id, 422);

            //loop through each line and update current stock
            // $dataForStock = [];

            // foreach ($request->scmMiLines as $scmMoLine) {
            //     $dataForStock[] = (new StockLedgerData)->out($scmMoLine['scm_material_id'], $scmMi->scm_warehouse_id, $scmMoLine['quantity']);
            // }

            // $dataForStockLedger = array_merge(...$dataForStock);

            // $scmMi->stockable()->createMany($dataForStockLedger);



            DB::commit();

            return response()->success('Data created succesfully', $shortage, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmMi $movementIn
     * @return JsonResponse
     */
    public function show(ScmMi $movementIn): JsonResponse
    {
        try {
            $movementIn->load(
                'scmMiLines.scmMaterial',
                'fromWarehouse',
                'toWarehouse',
                'createdBy',
                'scmMmr',
            );

            $scmMiLines = $movementIn->scmMiLines->map(function ($scmMoLine) use ($movementIn) {
                $lines = [
                    'scm_material_id' => $scmMoLine->scm_material_id,
                    'scmMaterial' => $scmMoLine->scmMaterial,
                    'unit' => $scmMoLine->unit,
                    'quantity' => $scmMoLine->quantity,
                    'mmr_quantity' => $scmMoLine->scmMmrLine->quantity,
                    'max_quantity' => $scmMoLine->scmMmrLine->scmMiLines->sum('quantity') - $scmMoLine->quantity,
                    'mo_composite_key' => $scmMoLine->mo_composite_key ?? null,
                ];

                return $lines;
            });

            data_forget($movementIn, 'scmMiLines');
            $movementIn->scmMiLines = $scmMiLines;

            return response()->success('Data updated sucessfully!', $movementIn, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmMIRequest $request
     * @param ScmMi $movementIn
     * @return JsonResponse
     */
    public function update(ScmMIRequest $request, ScmMi $movementIn): JsonResponse
    {
        $requestData = $request->except('ref_no', 'mi_composite_key');

        try {
            $movementIn->update($requestData);

            $movementIn->scmMiLines()->delete();

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmMiLines, $movementIn->id, 'scm_material_id', 'mi');

            $movementIn->scmMiLines()->createMany($linesData);

            return response()->success('Data updated sucessfully!', $movementIn, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmMi $movementIn
     * @return JsonResponse
     */
    public function destroy(ScmMi $movementIn): JsonResponse
    {
        try {
            $movementIn->scmMiLines()->delete();
            $movementIn->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function getMmrWiseMiData(Request $request)
    {
        try {
            // $movementIn = ScmMi::where('scm_mmr_id', $request->scm_mmr_id)->with(
            //     'scmMiLines.scmMaterial',
            //     'fromWarehouse',
            //     'toWarehouse',
            //     'createdBy',
            //     'scmMmr',
            // )->latest()->first();
            // $scmMiLines = $movementIn->scmMiLines->map(function ($scmMoLine) use ($movementIn) {
            //     $lines = [
            //         'scm_material_id' => $scmMoLine->scm_material_id,
            //         'scmMaterial' => $scmMoLine->scmMaterial,
            //         'unit' => $scmMoLine->unit,
            //         'quantity' => $scmMoLine->quantity,
            //         'mmr_quantity' => $scmMoLine->scmMmrLine->quantity,
            //         'max_quantity' => $scmMoLine->scmMmrLine->scmMiLines->sum('quantity') - $scmMoLine->quantity,
            //         'mo_composite_key' => $scmMoLine->mo_composite_key ?? null,
            //     ];

            //     return $lines;
            // });

            // data_forget($movementIn, 'scmMiLines');
            // $movementIn->scmMiLines = $scmMiLines;

            if ($request->mmr_id != null) {
                $scmMmr = ScmMo::query()
                    ->with('scmMoLines', 'fromWarehouse', 'toWarehouse', 'createdBy')
                    ->where('scm_mmr_id', $request->mmr_id)
                    ->first();

                $data = [
                    'scmMiLines' => $scmMmr->scmMoLines->map(function ($item) use ($scmMmr) {
                        return [
                            'scmMaterial' => $item->scmMaterial,
                            'scm_material_id' => $item->scmMaterial->id,
                            'unit' => $item->scmMaterial->unit,
                            'quantity' => $item->quantity,
                            'mo_quantity' => $item->quantity,
                            'mmr_quantity' => $item->scmMmrLine->quantity,
                            'mmr_composite_key' => $item->mmr_composite_key,
                            'mo_composite_key' => $item->mo_composite_key,
                            'current_stock' => (new CurrentStock)->count($item->scm_material_id, $scmMmr->scm_warehouse_id),
                         
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
