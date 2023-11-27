<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SupplyChain\Entities\ScmStockLedger;

class SupplyChainController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('supplychain::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('supplychain::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('supplychain::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('supplychain::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Retrieves the current stock of a material in a warehouse.
     *
     * @param int $materialId
     * @param int $warehouseId
     * @param int $qty
     * @param string $method
     * 
     * @return void
     */
    public function getCurrentStock($materialId = 1, $warehouseId = 1, $qty = 150, $method = 'lifo')
    {
        $currentStock1 = ScmStockLedger::query()
            ->where('scm_material_id', $materialId)
            ->where('scm_warehouse_id', $warehouseId)
            ->whereNull('recievable_id')
            ->get()
            ->filter(function ($item) {
                $StockIn = $item->quantity;
                $StockOut = $item->child->sum('quantity');
                return $StockIn + $StockOut > 0;
            });

        if ($method == 'lifo') {
            $currentStock1 = $currentStock1->sortByDesc('received_date');
        } else {
            $currentStock1 = $currentStock1->sortBy('received_date');
        }

        $stockOutArray = [];
        $outQty = $qty;
        foreach ($currentStock1 as $key => $value) {
            $StockIn = $value->quantity;
            $StockOut = $value->child->sum('quantity');
            $currentStock = $StockIn + $StockOut;
            if ($currentStock > $outQty) {
                $stockOutArray[] = [
                    'scm_material_id'           => $value->scm_material_id,
                    'scm_warehouse_id'          => $value->scm_warehouse_id,
                    'acc_cost_center_id'        => $value->acc_cost_center_id,
                    'parent_id'                 => $value->id,
                    'recievable_type'           => $value->stockable_type,
                    'recievable_id'             => $value->stockable_id,
                    'quantity'                  => -$outQty,
                    'gross_unit_price'          => $value->gross_unit_price,
                    'gross_foreign_unit_price'  => $value->gross_foreign_unit_price,
                    'net_unit_price'            => $value->net_unit_price,
                    'net_foreign_unit_price'    => $value->net_foreign_unit_price,
                    'currency'                  => $value->currency,
                    'exchange_rate'             => $value->exchange_rate,
                    'business_unit'             => $value->business_unit,
                    'received_date'             => $value->received_date,
                ];
                break;
            } else {
                $stockOutArray[] = [
                    'scm_material_id'           => $value->scm_material_id,
                    'scm_warehouse_id'          => $value->scm_warehouse_id,
                    'acc_cost_center_id'        => $value->acc_cost_center_id,
                    'parent_id'                 => $value->id,
                    'recievable_type'           => $value->stockable_type,
                    'recievable_id'             => $value->stockable_id,
                    'quantity'                  => -$currentStock,
                    'gross_unit_price'          => $value->gross_unit_price,
                    'gross_foreign_unit_price'  => $value->gross_foreign_unit_price,
                    'net_unit_price'            => $value->net_unit_price,
                    'net_foreign_unit_price'    => $value->net_foreign_unit_price,
                    'currency'                  => $value->currency,
                    'exchange_rate'             => $value->exchange_rate,
                    'business_unit'             => $value->business_unit,
                    'received_date'             => $value->received_date,
                ];
                $outQty = $outQty - $currentStock;
            }
        }


        dd($currentStock1, $stockOutArray);
    }
}
