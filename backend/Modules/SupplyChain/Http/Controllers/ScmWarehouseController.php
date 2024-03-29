<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmWarehouse;

class ScmWarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $scmWarehouses = ScmWarehouse::query()
                ->with('scmWarehouseContactPersons')
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
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $warehouse = ScmWarehouse::create($request->all());
            $warehouse->scmWarehouseContactPersons()->createMany($request->scmWarehouseContactPersons);

            DB::commit();

            return response()->success('Data created successfully', $warehouse, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmWarehouse $warehouse
     * @return JsonResponse
     */
    public function show(ScmWarehouse $warehouse): JsonResponse
    {
        try {
            return response()->success('data', $warehouse->load('scmWarehouseContactPersons'), 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param ScmWarehouse $warehouse
     * @return JsonResponse
     */
    public function update(Request $request, ScmWarehouse $warehouse)
    {
        try {
            $warehouse->update($request->all());

            $warehouse->scmWarehouseContactPersons()->createMany($request->scmWarehouseContactPersons);

            return response()->success('Data updated sucessfully!', $warehouse, 202);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmWarehouse $warehouse
     * @return JsonResponse
     */
    public function destroy(ScmWarehouse $warehouse): JsonResponse
    {
        try {
            $warehouse->scmWarehouseContactPersons()->delete();
            $warehouse->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchWarehouse(Request $request)
    {
        if ($request->business_unit != 'ALL') {
            $warehouse = ScmWarehouse::query()
                ->with('scmWarehouseContactPersons')
                ->whereBusinessUnit($request->business_unit)
                ->where('name', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('name')
                ->limit(10)
                ->get();
        } else {
            $warehouse = [];
        }

        return response()->success('Search result', $warehouse, 200);
    }
}
