<?php

namespace Modules\SupplyChain\Services;

use Modules\SupplyChain\Entities\ScmStockLedger;

class CurrentStock
{
    public static function count($scm_material_id, $scm_warehouse_id)
    {
        $currentStock = ScmStockLedger::query()
            ->where([
                'scm_material_id' => $scm_material_id,
                'scm_warehouse_id' => $scm_warehouse_id
            ])
            ->sum('quantity');

        return $currentStock;
    }
}
