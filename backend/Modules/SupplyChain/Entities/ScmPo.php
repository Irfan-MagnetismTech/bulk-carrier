<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMrr;
use Modules\SupplyChain\Entities\ScmPoLine;
use Modules\SupplyChain\Entities\ScmPoTerm;
use Modules\SupplyChain\Entities\ScmVendor;
use Modules\SupplyChain\Entities\ScmLcRecord;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ScmPo extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'ref_no',
        'scm_cs_id',
        'date',
        'scm_vendor_id',
        'scm_warehouse_id',
        'acc_cost_center_id',
        'currency',
        'usd_to_bdt',
        'foreign_to_usd',
        'discount',
        'vat',
        'business_unit',
        'created_by',
        'sub_total',
        'total_amount',
        'net_amount',
        'pr_date',
        'purchase_center',
        'remarks',
        'is_closed',
        'closed_by',
        'closed_at',
        'closing_remarks',
        'status',
    ];

    public function scmPoLines(): HasMany
    {
        return $this->hasMany(ScmPoLine::class);
    }

    public function scmPoTerms(): HasMany
    {
        return $this->hasMany(ScmPoTerm::class);
    }

    public function scmVendor(): BelongsTo
    {
        return $this->belongsTo(ScmVendor::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    // public function scmPr(): BelongsTo
    // {
    //     return $this->belongsTo(ScmPr::class);
    // }

    public function scmCs(): BelongsTo
    {
        return $this->belongsTo(ScmCs::class);
    }

    public function scmLcRecords(): HasMany
    {
        return $this->hasMany(ScmLcRecord::class);
    }

    public function scmMrrs(): HasMany
    {
        return $this->hasMany(ScmMrr::class);
    }

    public function scmPoItems(): HasManyThrough
    {
        return $this->hasManyThrough(ScmPoItem::class, ScmPoLine::class);
    }
}
