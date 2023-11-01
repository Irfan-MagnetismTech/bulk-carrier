<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmPo;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Entities\ScmPrLine;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Http\Requests\ScmPoRequest;

class ScmPoController extends Controller
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
            $scmWarehouses = ScmPo::query()
                ->with('scmPoLines', 'scmPoTerms', 'scmVendor', 'scmWarehouse', 'scmPr')
                ->latest()
                ->when(request()->business_unit != "ALL", function ($query) {
                    $query->where('business_unit', request()->business_unit);
                })
                ->paginate(10);

            return response()->success('Data list', $scmWarehouses, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmPoRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $requestData['ref_no'] = $this->uniqueId->generate(ScmPo::class, 'PO');
            
            
            $scmPo = ScmPo::create($request->all());

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmPoLines, $scmPo->id, 'scm_material_id', 'po');
            
            // return response()->json($linesData, 422);    

            $scmPo->scmPoLines()->createUpdateOrDelete($linesData);
            $scmPo->scmPoTerms()->createUpdateOrDelete($request->scmPoTerms);

            DB::commit();

            return response()->success('Data created successfully', $scmPo, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmPo $purchaseOrder
     * @return JsonResponse
     */
    public function show(ScmPo $purchaseOrder): JsonResponse
    {
        try {
            return response()->success('data', $purchaseOrder->load('scmPoLines.scmMaterial', 'scmPoTerms','scmVendor', 'scmWarehouse', 'scmPr'), 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param ScmPo $purchaseOrder
     * @return JsonResponse
     */
    public function update(ScmPoRequest $request, ScmPo $purchaseOrder): JsonResponse
    {
        $requestData = $request->except('ref_no', 'pr_composite_key', 'po_composite_key');

        try {
            $purchaseOrder->update($request->all());
            $purchaseOrder->scmPoLines()->createUpdateOrDelete($requestData);
            $purchaseOrder->scmPoTerms()->createUpdateOrDelete($request->scmPoTerms);

            return response()->success('Data updated sucessfully!', $purchaseOrder, 202);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmPo $purchaseOrder
     * @return JsonResponse
     */
    public function destroy(ScmPo $purchaseOrder): JsonResponse
    {
        try {
            $purchaseOrder->scmPoTerms()->delete();
            $purchaseOrder->scmPoLines()->delete();
            $purchaseOrder->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchWarehouse(Request $request): JsonResponse
    {
        if ($request->business_unit != 'ALL') {
            $scmPo = ScmPo::query()
                ->with('scmPoLines', 'scmPoTerms')
                ->whereBusinessUnit($request->business_unit)
                ->where('name', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('name')
                ->limit(10)
                ->get();
        } else {
            $scmPo = [];
        }

        return response()->success('Search result', $scmPo, 200);
    }

    //get getPoOrPoCsWisePrData
    public function getPoOrPoCsWisePrData(Request $request): JsonResponse
    {

        // {
        //     scmWarehouse: '', //ok
        //     scm_warehouse_id: '', //ok
        //     pr_no: null,  //ok
        //     scm_pr_id: null, //ok
        //     scmPr: null, //ok
        //     pr_date: '', //ok
        //     cs_no: '',//if cs
        //     scm_cs_id: '',//if cs
        //     scmCs: null,//if cs
        //     scmPoLines: [
        //                     {
        //                         scmMaterial: '',
        //                         scm_material_id: '',
        //                         unit: '',
        //                         brand: '',
        //                         model: '',
        //                         quantity: 0.0,
        //                         rate: 0.0,//if cs
        //                         total_price: 0.0,//if cs
        //                     }
        //                 ], 
        //     }
        try {
            if ($request->pr_id != null) {
                $scmPr = ScmPr::query()
                    ->with([
                        'scmWarehouse',
                        'scmPrLines.scmMaterial',
                    ])
                    ->where('id', $request->pr_id)
                    ->first();

                $data = [
                    'scmWarehouse' => $scmPr->scmWarehouse,
                    'scm_warehouse_id' => $scmPr->scm_warehouse_id,
                    'pr_no' => $scmPr->ref_no,
                    'scm_pr_id' => $scmPr->id,
                    'scmPr' => $scmPr,
                    'pr_date' => $scmPr->raised_date,
                    'business_unit' => $scmPr->business_unit,
                    'scmPoLines' => $scmPr->scmPrLines->map(function ($item) {
                        return [
                            'scmMaterial' => $item->scmMaterial,
                            'scm_material_id' => $item->scmMaterial->id,
                            'unit' => $item->scmMaterial->unit,
                            'brand' => $item->brand,
                            'model' => $item->model,
                            'quantity' => $item->quantity,
                            'pr_composite_key' => $item->pr_composite_key,
                            // 'rate' => $item->rate,
                            // 'total_price' => $item->total_price
                        ];
                    })
                ];
            } else {
                // $scmCs = ScmCs::query()
                // ->with('scmWarehouse', 'scmPr')
                // ->where([['id', $request->cs_id], ['scm_pr_id', $request->pr_id]])
                // ->get();
            }


            return response()->success('data', $data, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }
}
