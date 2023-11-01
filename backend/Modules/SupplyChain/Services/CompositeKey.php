<?php

namespace Modules\SupplyChain\Services;

class CompositeKey
{
    /**
     * this function is used to generate unique id for any model
     * 
     * @param int $model_id
     * @param string $infix
     * @param int $column_id
     * @return string
     */
    public function generate(int $model_id, string $infix, int $column_id): string
    {
        return $model_id . '-' . strtoupper($infix) . '-' . $column_id;
    }

    /**
     * Generates an array with a composite key.
     *
     * @param array $lines
     * @param int $parentModelId
     * @param string $columnName
     * @param string $infix
     * @return array
     */
    public function generateArrayWithCompositeKey(array $lines, int $parentModelId, string $columnName, string $infix)
    {
        foreach ($lines as &$line) {
            if (isset($line[$columnName])) {
                $parentModelId = $parentModelId;
                $infix = $infix;
                $line[$infix . '_composite_key'] = $parentModelId . '-' . strtoupper($infix) . '-' . $line[$columnName];
            }
        }

        return $lines;
    }
}
