<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmMi;
use Modules\SupplyChain\Entities\ScmMo;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Services\CurrentStock;
use Modules\SupplyChain\Entities\ScmMiShortage;
use Modules\SupplyChain\Entities\ScmMiShortageLine;
use Modules\SupplyChain\Entities\ScmStockLedger;
use Modules\SupplyChain\Services\StockLedgerData;
use Modules\SupplyChain\Http\Requests\ScmMiRequest;

class ScmMiController extends Controller
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
            $movementOuts = ScmMi::with(
                'scmMiLines.scmMaterial',
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
    public function store(ScmMiRequest $request)
    {
        $requestData = $request->except('ref_no', 'mi_composite_key');

        // $requestData['ref_no'] = UniqueId::generate(ScmMi::class, 'MI');

        try {
            DB::beginTransaction();

            $scmMi = ScmMi::create($requestData);

            $ref_no = UniqueId::generate($scmMi->id, 'MI');
            $scmMi->update(['ref_no' => $ref_no]);

            $linesData = CompositeKey::generateArray($request->scmMiLines, $scmMi->id, 'scm_material_id', 'mi');
            $scmMi->scmMiLines()->createMany($linesData);

            if ($request->scmMiShortage['shortage_type'] != "") {
                $scmMi->scmMiShortage()->create($request->scmMiShortage);

                $this->shortageLinesData($request, $scmMi);

                $stockInFromShortage = [];
                $stockOutFromShortage = [];

                foreach ($request->scmMiShortage['scmMiShortageLines'] as $key => $value) {

                    $stockInFromShortage = [
                        'scm_material_id' => $value['scm_material_id'],
                        'scm_warehouse_id' => $request->scmMiShortage['scm_warehouse_id'],
                        'acc_cost_center_id' => $request->scmMiShortage['acc_cost_center_id'],
                        'quantity' => $value['quantity'],
                        'business_unit' => $request->business_unit
                    ];

                    $miStockable = $scmMi->stockable()->create($stockInFromShortage);

                    $stockOutFromShortage = [
                        'scm_material_id' => $value['scm_material_id'],
                        'recievable_type`' => ScmMi::class,
                        'recievable_id' =>  $scmMi->scmMiShortage->scmMiShortageLines[$key]->id,
                        'scm_warehouse_id' => $request->scmMiShortage['scm_warehouse_id'],
                        'acc_cost_center_id' => $request->scmMiShortage['acc_cost_center_id'],
                        'quantity' => $value['quantity'] * -1,
                        'parent_id' => $miStockable->id,
                        'business_unit' => $request->business_unit
                    ];

                    $scmMi->scmMiShortage->stockable()->create($stockOutFromShortage);
                }
            }

            StockLedgerData::insert($scmMi, $request->scmMiLines);

            DB::commit();

            return response()->success('Data created succesfully', $scmMi, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Inserts a shortage into the database.
     *
     * @param mixed $request
     * @param mixed $scmMi.
     * @return void
     */
    private function shortageLinesData($request, $scmMi)
    {
        $shortageLines = $request->scmMiShortage['scmMiShortageLines'];

        foreach ($shortageLines as $key => $shortageLine) {
            $materialId = $shortageLine['scm_material_id'];
            $miLine = $scmMi->scmMiLines()->where('scm_material_id', $materialId)->first();

            if ($miLine && $miLine->mi_composite_key) {
                $shortageLines[$key]['mi_composite_key'] = $miLine->mi_composite_key;
            }
        }

        $scmMi->scmMiShortage->scmMiShortageLines()->createMany($shortageLines);
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
                'scmMiShortage.scmMiShortageLines.scmMaterial',
                'scmMiShortage.scmWarehouse',
                'scmMiLines.scmMaterial',
                'fromWarehouse',
                'toWarehouse',
                'createdBy',
                'scmMmr',
                'scmMo'
            );

            $scmMiLines = $movementIn->scmMiLines->map(function ($scmMiLine) use ($movementIn) {
                $lines = [
                    'id' => $scmMiLine->id,
                    'scm_material_id' => $scmMiLine->scm_material_id,
                    'scmMaterial' => $scmMiLine->scmMaterial,
                    'unit' => $scmMiLine->unit,
                    'quantity' => $scmMiLine->quantity,
                    'mo_quantity' => $scmMiLine->scmMoLine->quantity,
                    'mmr_quantity' => $scmMiLine->scmMmrLine->quantity,
                    'max_quantity' => $scmMiLine->scmMoLine->quantity - $scmMiLine->scmMoLine->scmMiLines->sum('quantity'),
                    'mi_composite_key' => $scmMiLine->mi_composite_key ?? null,
                    'mo_composite_key' => $scmMiLine->mo_composite_key,
                    'mmr_composite_key' => $scmMiLine->mmr_composite_key,
                    'remarks' => $scmMiLine->remarks,
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
    public function update(ScmMIRequest $request, ScmMi $movementIn)
    {
        $requestData = $request->except('ref_no', 'mi_composite_key');

        try {
            DB::beginTransaction();

            $movementIn->update($requestData);

            $movementIn->scmMiLines()->delete();
            $movementIn->stockable()->delete();

            $linesData = CompositeKey::generateArray($request->scmMiLines, $movementIn->id, 'scm_material_id', 'mi');

            $movementIn->scmMiLines()->createMany($linesData);

            StockLedgerData::insert($movementIn, $request->scmMiLines);

            if ($request->scmMiShortage['shortage_type'] == "") {

                if ($movementIn->scmMiShortage) {
                    $movementIn->scmMiShortage->scmMiShortageLines()->delete();
                    $movementIn->scmMiShortage->stockable()->delete();
                    $movementIn->scmMiShortage->delete();
                }
            }

            if ($request->scmMiShortage['shortage_type'] != "") {

                if ($movementIn->scmMiShortage) {
                    $movementIn->scmMiShortage->scmMiShortageLines()->delete();
                    $movementIn->scmMiShortage->stockable()->delete();

                    $movementIn->scmMiShortage()->update([
                        'scm_mi_id' => $movementIn->id,
                        'shortage_type' => $request->scmMiShortage['shortage_type'],
                        'scm_warehouse_id' => $request->scmMiShortage['scm_warehouse_id'],
                        'acc_cost_center_id' => $request->scmMiShortage['acc_cost_center_id'] ?? null,
                        'business_unit' => $request->business_unit
                    ]);
                } else {
                    $scmMiShortage = $movementIn->scmMiShortage()->create($request->scmMiShortage);
                }

                $shortageLines = $request->scmMiShortage['scmMiShortageLines'];

                foreach ($shortageLines as $key => $shortageLine) {
                    $materialId = $shortageLine['scm_material_id'];
                    $shortageLine['scm_mi_shortage_id'] = isset($scmMiShortage) ? $scmMiShortage->id : $movementIn->scmMiShortage->id;

                    $miLine = $movementIn->scmMiLines()->where('scm_material_id', $materialId)->first();

                    if ($miLine && $miLine->mi_composite_key) {
                        $shortageLine['mi_composite_key'] = $miLine->mi_composite_key;
                    }
                    ScmMiShortageLine::create($shortageLine);
                }

                $stockInFromShortage = [];
                $stockOutFromShortage = [];

                foreach ($request->scmMiShortage['scmMiShortageLines'] as $key => $value) {

                    $stockInFromShortage = [
                        'scm_material_id' => $value['scm_material_id'],
                        'scm_warehouse_id' => $request->scmMiShortage['scm_warehouse_id'],
                        'acc_cost_center_id' => $request->scmMiShortage['acc_cost_center_id'],
                        'quantity' => $value['quantity'],
                        'business_unit' => $request->business_unit
                    ];

                    $miStockable = $movementIn->stockable()->create($stockInFromShortage);

                    $stockOutFromShortage = [
                        'scm_material_id' => $value['scm_material_id'],
                        'recievable_type`' => ScmMi::class,
                        'recievable_id' => isset($scmMiShortage) ? $scmMiShortage->scmMiShortageLines[$key]->id : $movementIn->scmMiShortage->scmMiShortageLines[$key]->id,
                        'scm_warehouse_id' => $request->scmMiShortage['scm_warehouse_id'],
                        'acc_cost_center_id' => $request->scmMiShortage['acc_cost_center_id'],
                        'quantity' => $value['quantity'] * -1,
                        'parent_id' => $miStockable->id,
                        'business_unit' => $request->business_unit
                    ];

                    isset($scmMiShortage) ? $scmMiShortage->stockable()->create($stockOutFromShortage) : $movementIn->scmMiShortage->stockable()->create($stockOutFromShortage);
                }
            }

            DB::commit();

            return response()->success('Data updated sucessfully!', $movementIn, 202);
        } catch (\Exception $e) {
            DB::rollBack();

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
            DB::beginTransaction();
            $movementIn->stockable()->delete();
            $movementIn->scmMiLines()->delete();

            if ($movementIn->scmMiShortage) {
                $movementIn->scmMiShortage->stockable()->delete();
                $movementIn->scmMiShortage->scmMiShortageLines()->delete();
                $movementIn->scmMiShortage()->delete();
            }
            $movementIn->delete();

            DB::commit();
            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {
            DB::rollBack();
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
                            'current_stock' => CurrentStock::count($item->scm_material_id, $scmMmr->scm_warehouse_id),

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

    function getMoWiseMiData(Request $request)
    {
        try {


            if ($request->mo_id != null) {
                $scmMo = ScmMo::query()
                    ->with('scmMoLines', 'fromWarehouse', 'toWarehouse', 'createdBy')
                    ->where('id', $request->mo_id)
                    ->first();

                $data = [
                    'scmMiLines' => $scmMo->scmMoLines->map(function ($item) use ($scmMo) {
                        return [
                            'scmMaterial' => $item->scmMaterial,
                            'scm_material_id' => $item->scmMaterial->id,
                            'unit' => $item->scmMaterial->unit,
                            'quantity' => $item->quantity,
                            'mo_quantity' => $item->quantity,
                            'mmr_quantity' => $item->scmMmrLine->quantity,
                            'mmr_composite_key' => $item->mmr_composite_key,
                            'mo_composite_key' => $item->mo_composite_key,
                            'current_stock' => CurrentStock::count($item->scm_material_id, $scmMo->to_warehouse_id),

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
