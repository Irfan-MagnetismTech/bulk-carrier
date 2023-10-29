<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmPo;
use Illuminate\Contracts\Support\Renderable;

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
}
