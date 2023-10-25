<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\SupplyChain\Entities\ScmOpeningStockLine;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmOpeningStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_warehouse_id', 'date', 'business_unit',
    ];

    public function scmOpeningStockLines(): HasMany
    {
        return $this->hasMany(ScmOpeningStockLine::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class)->withDefault();
    }

    public function stockable()
    {
        return $this->morphMany(ScmStockLedger::class, 'stockable');
    }
}
