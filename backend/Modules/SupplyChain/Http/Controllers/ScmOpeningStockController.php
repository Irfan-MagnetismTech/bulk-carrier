<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmOpeningStock;

class ScmOpeningStockController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $scm_opening_stocks = ScmOpeningStock::query()
                ->with('scmOpeningStockLines')
                ->latest()
                ->when(request()->business_unit != "ALL", function ($q) {
                    $q->where('business_unit', request()->business_unit);
                })
                ->paginate(10);;

            return response()->success('Data list', $scm_opening_stocks, 200);
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
            $scm_opening_stock = ScmOpeningStock::create($request->all());
            $scm_opening_stock->scmOpeningStockLines()->createMany($request->scmOpeningStockLines);

            $stock_ledger_data = [];
            collect($request->scmOpeningStockLines)->map(function ($scm_opening_stock_line) use ($scm_opening_stock, &$stock_ledger_data) {
                $stock_ledger_data[] = [
                    'scm_material_id' => $scm_opening_stock_line['scm_material_id'],
                    'scm_warehouse_id' => $scm_opening_stock->scm_warehouse_id,
                    'acc_cost_center_id' => null,
                    'parent_id' => null,
                    'quantity' => $scm_opening_stock_line['quantity'],
                    'gross_unit_price' => $scm_opening_stock_line['rate'],
                    'net_unit_price' => $scm_opening_stock_line['rate'],
                    'currency' => 'BDT',
                    'received_date' => $scm_opening_stock->date,
                    'business_unit' => $scm_opening_stock->business_unit,
                ];
            });

            $scm_opening_stock->stockable()->createMany($stock_ledger_data);

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
    public function update(Request $request, ScmOpeningStock $opening_stock)
    {
        try {
            $opening_stock->update($request->all());

            $opening_stock->scmOpeningStockLines()->createUpdateOrDelete($request->scmOpeningStockLines);

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

    public function searchOpeningStock(Request $request)
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
