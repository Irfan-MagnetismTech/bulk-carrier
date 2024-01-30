<?php

namespace Services;

use App\Models\LastInserted;
use Illuminate\Support\Facades\DB;

/**
 * @package Services
 * 
 * @class-type Service
 */
class UniqueId
{
    /**
     * Generates a unique ID for any model.
     * 
     * @param object $model
     * @param string $prefix
     * 
     * @return string
     */
    public static function generate(object $model, string $prefix): string
    {
        try {
            DB::beginTransaction();

            $lastInsertedRecord = LastInserted::query()
                ->where('last_inserted_type', get_class($model))
                ->lockForUpdate()
                ->first();

            if ($lastInsertedRecord) {
                $uniqueKey = $prefix . '-' . ($lastInsertedRecord->last_inserted_id + 1);

                $lastInsertedRecord->update([
                    'last_inserted_id' => $lastInsertedRecord->last_inserted_id + 1,
                ]);
            } else {
                LastInserted::query()->create([
                    'last_inserted_type' => get_class($model),
                    'last_inserted_id' => 1
                ]);

                $uniqueKey = $prefix . '-1';
            }

            DB::commit();

            return $uniqueKey;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
