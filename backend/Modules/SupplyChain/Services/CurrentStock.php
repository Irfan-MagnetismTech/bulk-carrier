<?php

namespace Modules\SupplyChain\Services;

use Modules\SupplyChain\Entities\ScmStockLedger;

/**
 * @package Modules\SupplyChain\Services
 * 
 * @class-type Service
 */
class CurrentStock
{
    /**
     * Counts the current stock of a material in a warehouse.
     *
     * @param int $scm_material_id
     * @param int $scm_warehouse_id
     * @param string|null $toDate (optional)
     * @return int
     */
    public static function count($scm_material_id, $scm_warehouse_id, $toDate = null): int
    {
        $currentStock = ScmStockLedger::query()
            ->where([
                'scm_material_id' => $scm_material_id,
                'scm_warehouse_id' => $scm_warehouse_id
            ])
            ->when(!is_null($toDate), function ($query) use ($toDate) {
                $query->where('date', '<=', $toDate);
            })
            ->sum('quantity');

        return (int) $currentStock;
    }
}
