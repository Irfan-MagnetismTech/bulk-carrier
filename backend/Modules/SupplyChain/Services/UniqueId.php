<?php

namespace Modules\SupplyChain\Services;

use Illuminate\Support\Facades\DB;

/**
 * @package Modules\SupplyChain\Services
 * 
 * @class-type Service
 */
class UniqueId
{
    /**
     * Generates a unique ID for any model.
     *
     * @param int $modelId
     * @param string $prefix
     * @return string
     */
    public static function generate(int $modelId, string $prefix)
    {
        // return DB::transaction(function () use ($model, $prefix) {
        //     // Lock the row for update to prevent concurrent access
        //     $latestModel = $model::latest()->lockForUpdate()->first();
        //     $tableName = (new $model)->getTable();

        //     $version = DB::select( DB::raw("select version()") )[0]->{'version()'};
        //     return response()->json($version, 422);

        //     if (strpos($version, 'MariaDB') === false) {
        //         DB::statement('SET information_schema_stats_expiry = 0');
        //     }

        //     $nextId = DB::table('information_schema.tables')
        //         ->where('table_name', $tableName)
        //         ->where('table_schema', DB::raw('DATABASE()'))
        //         ->value('AUTO_INCREMENT');

        //     return strtoupper($prefix) . '-' . ($latestModel ? $nextId : 1);
        // });

            return strtoupper($prefix) . '-' . $modelId;
    }
}
