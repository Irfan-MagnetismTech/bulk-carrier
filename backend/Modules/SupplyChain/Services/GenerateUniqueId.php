<?php

namespace Modules\SupplyChain\Services;

class GenerateUniqueId
{
    /**
     * @param model $model
     * @param string $prefix
     * @return string - unique id
     * 
     * this function is used to generate unique id for any model
     */
    public function generate($model, $prefix): string
    {
        $lastIndentData = $model::latest()->first();
        if ($lastIndentData) {
            if (now()->format('Y') != date('Y', strtotime($lastIndentData->created_at))) {
                return strtoupper($prefix) . '-' . now()->format('Y') . '-' . 1;
            } else {
                return strtoupper($prefix) . '-' . now()->format('Y') . '-' . $lastIndentData->id + 1;
            }
        } else {
            return strtoupper($prefix) . '-' . now()->format('Y') . '-' . 1;
        }
    }
}
