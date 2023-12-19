<?php

namespace Modules\SupplyChain\Services;

class UniqueId
{
    /**
     * Generates a unique ID for any model.
     *
     * @param Model $model The model to generate the ID for.
     * @param string $prefix The prefix to prepend to the ID.
     * @return string The unique ID.
     */
    public function generate($model, string $prefix): string
    {
        $currentYear = now()->format('Y');
        $latestModel = $model::latest()->first();

        $lastYear = $latestModel ? date('Y', strtotime($latestModel->created_at)) : null;
        $lastId = $latestModel ? $latestModel->id : 0;

        $newId = ($currentYear != $lastYear) ? 1 : ($lastId + 1);

        return strtoupper($prefix) . '-' . $currentYear . '-' . $newId;
    }

    
    public function generate2($model, string $prefix): string
    {
        $latestModel = $model::latest()->first();
        $currentYear = now()->format('Y');

        if ($latestModel) {
            $lastYear = date('Y', strtotime($latestModel->created_at));
            $lastId = $latestModel->id;

            if ($currentYear != $lastYear) {
                return strtoupper($prefix) . '-' . $currentYear . '-' . 1;
            }

            return strtoupper($prefix) . '-' . $currentYear . '-' . ($lastId + 1);
        } else {
            return strtoupper($prefix) . '-' . $currentYear . '-' . 1;
        }
    }
}
