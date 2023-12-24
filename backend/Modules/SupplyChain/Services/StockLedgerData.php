<?php

namespace Modules\SupplyChain\Services;

use Modules\SupplyChain\Entities\ScmStockLedger;

class StockLedgerData
{
    /**
     * Inserts stock ledger data related to a parent model.
     *
     * @param mixed $parentModel
     * @param array $lines
     * @param bool $os (opening stock)
     *
     * @return void
     */
    public function insert($parentModel, $lines, $os = false)
    {
        $stock_ledger_data = [];
        collect($lines)->map(function ($line) use ($parentModel, &$stock_ledger_data, $os) {
            $stock_ledger_data[] = [
                'scm_material_id' => $line['scm_material_id'],
                'scm_warehouse_id' => $parentModel->scm_warehouse_id ?? $parentModel->to_warehouse_id,
                'acc_cost_center_id' => $parentModel->acc_cost_center_id ?? null,
                'quantity' => $line['quantity'],
                'gross_unit_price' => $line['rate'] ?? null,
                'net_unit_price' => $os ? $line['rate'] : $line['net_rate'] ?? null,
                'currency' => $line['currency'] ?? null,
                'received_date' => now(),
                'business_unit' => $parentModel->business_unit,
            ];
        });

        $parentModel->stockable()->createMany($stock_ledger_data);
        return $stock_ledger_data;
    }

    /**
     * Processes the outflow of a material from a warehouse.
     *
     * @param int $materialId
     * @param int $warehouseId
     * @param int $qty
     * @param string $method (Optional)
     * 
     * @return array Returns an array of stock outflow records.
     */
    public function out($materialId, $warehouseId, $qty, $method = 'fifo')
    {
        if ((new CurrentStock)->count($materialId, $warehouseId) < $qty) {
            return response()->json(['message' => 'Insufficient stock'], 422);
        }

        $currentStock = ScmStockLedger::query()
            ->where('scm_material_id', $materialId)
            ->where('scm_warehouse_id', $warehouseId)
            ->whereNull('recievable_id')
            ->get()
            ->filter(function ($item) {
                $stockIn = $item->quantity;
                $stockOut = $item->child->sum('quantity');
                return $stockIn + $stockOut > 0;
            });

        if ($method == 'lifo') {
            $currentStock = $currentStock->sortByDesc('received_date');
        } else {
            $currentStock = $currentStock->sortBy('received_date');
        }

        $stockOutArray = [];
        $outQty = $qty;
        foreach ($currentStock as $key => $value) {
            $stockIn = $value->quantity;
            $stockOut = $value->child->sum('quantity');
            $currentStock = $stockIn + $stockOut;
            if ($currentStock >= $outQty) {
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

        return $stockOutArray;
    }
}
