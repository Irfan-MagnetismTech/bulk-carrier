<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmPrLine;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmPr extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'ref_no', 'scm_warehouse_id', 'acc_cost_center_id', 'attachment', 'raised_date', 'remarks', 'purchase_center', 'is_critical', 'approved_date', 'business_unit', 'created_by'
    ];

    public function scmPrLines(): HasMany
    {
        return $this->hasMany(ScmPrLine::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }
}
