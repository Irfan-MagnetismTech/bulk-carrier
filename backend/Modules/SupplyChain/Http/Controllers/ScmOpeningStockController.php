<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmOpeningStock;
use Modules\SupplyChain\Services\StockLedgerData;

class ScmOpeningStockController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $scm_opening_stocks = ScmOpeningStock::query()
                ->with('scmOpeningStockLines', 'scmWarehouse')
                ->globalSearch($request->all());

            return response()->success('Data list', $scm_opening_stocks, 200);
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
            $scm_opening_stock = ScmOpeningStock::create($request->all());
            $scm_opening_stock->scmOpeningStockLines()->createMany($request->scmOpeningStockLines);
            (new StockLedgerData)->insert($scm_opening_stock, $request->scmOpeningStockLines, true);

            DB::commit();

            return response()->success('Data created succesfully', $scm_opening_stock, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmOpeningStock $opening_stock
     * @return JsonResponse
     */
    public function show(ScmOpeningStock $opening_stock): JsonResponse
    {
        try {
            return response()->success('data', $opening_stock->load('scmOpeningStockLines.scmMaterial', 'scmWarehouse'), 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param ScmOpeningStock $opening_stock
     * @return JsonResponse
     */
    public function update(Request $request, ScmOpeningStock $opening_stock): JsonResponse
    {
        try {
            $opening_stock->update($request->all());

            $opening_stock->scmOpeningStockLines()->createUpdateOrDelete($request->scmOpeningStockLines);

            $opening_stock->stockable()->delete();
            (new StockLedgerData)->insert($opening_stock, $request->scmOpeningStockLines, true);

            return response()->success('Data updated sucessfully!', $opening_stock, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmOpeningStock $opening_stock
     * @return JsonResponse
     */
    public function destroy(ScmOpeningStock $opening_stock): JsonResponse
    {
        try {
            $opening_stock->scmOpeningStockLines()->delete();
            $opening_stock->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchOpeningStock(Request $request): JsonResponse
    {
        if ($request->business_unit != 'ALL') {
            $opening_stock = ScmOpeningStock::query()
                ->with('scmOpeningStockLines')
                ->whereBusinessUnit($request->business_unit)
                ->where('name', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('name')
                ->limit(10)
                ->get();
        } else {
            $opening_stock = [];
        }

        return response()->success('Search result', $opening_stock, 200);
    }
}
