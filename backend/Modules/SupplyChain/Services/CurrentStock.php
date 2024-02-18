<?php

namespace Modules\SupplyChain\Services;

use Carbon\Carbon;
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
    public static function count(int $scm_material_id, int $scm_warehouse_id, string $toDate = null): int
    {
        $currentStock = ScmStockLedger::query()
            ->where([
                'scm_material_id' => $scm_material_id,
                'scm_warehouse_id' => $scm_warehouse_id
            ])
            ->when(!is_null($toDate), function ($query) use ($toDate) {
                $query->where('date', '<=', Carbon::parse($toDate)->endOfDay());
            })
            ->sum('quantity');

        return (int) $currentStock;
    }


    /**
     * Counts the stock in of a material in a warehouse.
     *
     * @param int $scm_material_id
     * @param int $scm_warehouse_id
     * @param string|null $toDate (optional)
     * @return int
     */
    // public static function countStockIn($scm_material_id, $scm_warehouse_id, $toDate = null): int
    // {
    //     $currentStockIn = ScmStockLedger::query()
    //         ->where([
    //             'scm_material_id' => $scm_material_id,
    //             'scm_warehouse_id' => $scm_warehouse_id
    //         ])
    //         ->whereNull('recievable_type')
    //         ->when(!is_null($toDate), function ($query) use ($toDate) {
    //             $query->where('date', '<=', $toDate);
    //         })
    //         ->sum('quantity');

    //     return $currentStockIn;
    // }

    /**
     * Counts the stock out of a material from a warehouse.
     *
     * @param int $scm_material_id
     * @param int $scm_warehouse_id
     * @param string|null $toDate (optional)
     * @return int
     */
    // public static function countStockOut($scm_material_id, $scm_warehouse_id, $toDate = null): int
    // {
    //     $currentStockIn = ScmStockLedger::query()
    //         ->where([
    //             'scm_material_id' => $scm_material_id,
    //             'scm_warehouse_id' => $scm_warehouse_id
    //         ])
    //         ->whereNotNull('recievable_type')
    //         ->when(!is_null($toDate), function ($query) use ($toDate) {
    //             $query->where('date', '<=', $toDate);
    //         })
    //         ->sum('quantity');

    //     return $currentStockIn;
    // }


    /**
     * Counts the stock in of a material in a warehouse.
     *
     * @param int $scm_material_id
     * @param int $scm_warehouse_id
     * @param string|null $fromDate (optional)
     * @param string|null $toDate (optional)
     * @return int
     */
    public static function countStockIn($scm_material_id, $scm_warehouse_id, $fromDate = null, $toDate = null): int
    {
        $currentStockIn = ScmStockLedger::query()
            ->where([
                'scm_material_id' => $scm_material_id,
                'scm_warehouse_id' => $scm_warehouse_id
            ])
            ->whereNull('recievable_type')
            ->when(!is_null($toDate), function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('date', [Carbon::parse($fromDate)->startOfDay(), Carbon::parse($toDate)->endOfDay()]);
            })
            ->sum('quantity');

        return $currentStockIn;
    }

    /**
     * Counts the stock out of a material from a warehouse.
     *
     * @param int $scm_material_id
     * @param int $scm_warehouse_id
     * @param string|null $fromDate (optional)
     * @param string|null $toDate (optional)
     * @return int
     */
    public static function countStockOut($scm_material_id, $scm_warehouse_id, $fromDate = null, $toDate = null): int
    {
        $currentStockOut = ScmStockLedger::query()
            ->where([
                'scm_material_id' => $scm_material_id,
                'scm_warehouse_id' => $scm_warehouse_id
            ])
            ->whereNotNull('recievable_type')
            ->when(!is_null($toDate), function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('date', [Carbon::parse($fromDate)->startOfDay(), Carbon::parse($toDate)->endOfDay()]);
            })
            ->sum('quantity');

        return $currentStockOut;
    }
}
