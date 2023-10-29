<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmPo;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Entities\ScmPrLine;

class ScmPoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $scmWarehouses = ScmPo::query()
                ->with('scmPoLines', 'scmPoTerms')
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
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $scmPo = ScmPo::create($request->all());
            $scmPo->scmPoLines()->createUpdateOrDelete($request->scmPoLines);
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
            return response()->success('data', $purchaseOrder->load('scmPoLines', 'scmPoTerms'), 200);
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
    public function update(Request $request, ScmPo $purchaseOrder): JsonResponse
    {
        try {
            $purchaseOrder->update($request->all());
            $purchaseOrder->scmPoLines()->createMany($request->scmPoLines);
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
        //     scmWarehouse: '',
        //     scm_warehouse_id: '',
        //     pr_no: null,
        //     scm_pr_id: null,
        //     scmPr: null,
        //     pr_date: '',
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
            if ($request->cs_id != '' && $request->cs_id != null) {
                $scmPr = ScmPr::query()
                ->with([
                    'scmWarehouse',
                    'scmPrLine' => function ($query) {
                        $query->select([
                            'scm_material_id',
                            'unit',
                            'brand',
                            'model',
                            'quantity',
                        ])->with('scmMaterial');
                    }
                ])
                ->where('id', $request->pr_id)
                ->get();
                $data = [
                    'scmWarehouse' => $scmPr[0]->scmWarehouse,
                    'scm_warehouse_id' => $scmPr[0]->scm_warehouse_id,
                    'pr_no' => $scmPr[0]->ref_no,
                    'scm_pr_id' => $scmPr[0]->id,
                    'scmPr' => $scmPr[0],
                    'pr_date' => $scmPr[0]->pr_date,
                    'scmPoLines' => $scmPr[0]->scmPrLine,
                ];
            } else {
                // $scmCs = ScmCs::query()
                // ->with('scmWarehouse', 'scmPr')
                // ->where([['id', $request->cs_id], ['scm_pr_id', $request->pr_id]])
                // ->get();
            }
            

            return response()->success('Search result', $scmPr, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }
   
}
