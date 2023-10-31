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
     * @param int $columnId
     * @param string $infix
     * @return array
     */
    public function generateArrayWithCompositeKey(array $lines, int $parentModelId, int $columnId, string $infix): array
    {
        foreach ($lines as &$line) {
            if (isset($line[$columnId])) {
                $parentModelId = $parentModelId;
                $infix = $infix;
                $compositeKey = $this->generate($parentModelId, $infix, $line[$columnId]);
                $line[$infix . '_composite_key'] = $compositeKey;
            }
        }

        return $lines;
    }
}
