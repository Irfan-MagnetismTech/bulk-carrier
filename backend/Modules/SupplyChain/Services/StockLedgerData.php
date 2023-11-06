<?php

namespace Modules\SupplyChain\Services;

class StockLedgerData
{
    /**
     * Inserts stock ledger data related to a parent model.
     *
     * @param mixed $parentModel
     * @param array $scm_opening_stock_lines
     * @param bool $os
     *
     * @return void
     */
    public function insert($parentModel, $scm_opening_stock_lines, $os = false): void
    {
        $stock_ledger_data = [];
        collect($scm_opening_stock_lines)->map(function ($linesData) use ($parentModel, &$stock_ledger_data, $os) {
            $stock_ledger_data[] = [
                'scm_material_id' => $linesData['scm_material_id'],
                'scm_warehouse_id' => $parentModel->scm_warehouse_id,
                'acc_cost_center_id' => null,
                'quantity' => $linesData['quantity'],
                'gross_unit_price' => $linesData['rate'] ?? null,
                'net_unit_price' => $os ? $linesData['rate'] : $linesData['net_rate'] ?? null,
                'currency' => $linesData['currency'] ?? null,
                'received_date' => now(),
                'business_unit' => $parentModel->business_unit,
            ];
        });

        $parentModel->stockable()->createMany($stock_ledger_data);
    }
}
