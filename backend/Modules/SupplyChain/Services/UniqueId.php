<?php

namespace Modules\SupplyChain\Services;

use Illuminate\Support\Facades\DB;

class UniqueId
{
    /**
     * Generates a unique ID for any model.
     *
     * @param string $model The model to generate the ID for.
     * @param string $prefix The prefix to prepend to the ID.
     * @return string The unique ID.
     */
    public static function generate(string $model, string $prefix): string
    {
        $currentYear = now()->format('Y');
        $latestModel = $model::latest()->first();
        $lastYear = $latestModel ? $latestModel->created_at->format('Y') : null;

        $tableName = (new $model)->getTable();
        DB::statement('SET information_schema_stats_expiry = 0');

        $nextId = DB::table('information_schema.tables')
            ->where('table_name', $tableName)
            ->where('table_schema', DB::raw('DATABASE()'))
            ->value('AUTO_INCREMENT');

        $newId = ($currentYear != $lastYear) ? 1 : $nextId;

        return strtoupper($prefix) . '-' . $currentYear . '-' . $newId;
    }
}
