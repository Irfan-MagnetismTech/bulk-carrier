<?php

namespace Modules\SupplyChain\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        $lastYear = $latestModel ? date('Y', strtotime($latestModel->created_at)) : null;
        // $lastId = $latestModel ? $latestModel->id : 0;

        $tableName = (new $model)->getTable();

        // Set information_schema_stats_expiry to 0
        DB::statement('SET information_schema_stats_expiry = 0');

        // Fetch the current auto-incremented value
        $nextId = DB::table('information_schema.tables')
            ->select('AUTO_INCREMENT')
            ->where('table_name', $tableName)
            ->where('table_schema', DB::raw('DATABASE()'))
            ->value('AUTO_INCREMENT');

        $newId = ($currentYear != $lastYear) ? 1 : ($nextId);

        return strtoupper($prefix) . '-' . $currentYear . '-' . $newId;
    }
}
