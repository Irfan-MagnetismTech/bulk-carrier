<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMi;
use Modules\SupplyChain\Traits\StockLedger;
use Modules\Accounts\Entities\AccCostCenter;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\SupplyChain\Entities\ScmMiShortageLine;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmMiShortage extends Model
{
    use HasFactory, StockLedger;

    protected $fillable = [
        'scm_mi_id',
        'shortage_type',
        'scm_warehouse_id',
        'acc_cost_center_id',
        'business_unit'
    ];

    public function scmMiShortageLines(): HasMany
    {
        return $this->hasMany(ScmMiShortageLine::class);
    }

    public function scmMi(): BelongsTo
    {
        return $this->belongsTo(ScmMi::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(AccCostCenter::class);
    }
}
