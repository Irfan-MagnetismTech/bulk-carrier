<?php

namespace Modules\SupplyChain\Traits;

use Modules\SupplyChain\Entities\ScmStockLedger;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait StockLedger
{
    /**
     * Get the stock ledgers associated with the stockable entity.
     *
     * @return MorphMany
     */
    public function stockable(): MorphMany
    {
        return $this->morphMany(ScmStockLedger::class, 'stockable');
    }

    /**
     * Get the stock ledgers associated with the receiveable entity.
     *
     * @return MorphMany
     */
    public function receiveable(): MorphMany
    {
        return $this->morphMany(ScmStockLedger::class, 'receiveable');
    }

    /**
     * Check if the entity can be deleted based on related stock ledgers.
     *
     * @return bool
     */
    public function isDeleteable(): bool
    {
        return $this->stockable->contains(function ($item) {
            return $item->receiveable()->exists();
        });
    }
}
