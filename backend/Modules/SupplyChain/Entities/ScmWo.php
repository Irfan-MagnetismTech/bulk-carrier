<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use App\Traits\UniqueKeyGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ScmWo extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel, UniqueKeyGenerator;

    protected $fillable = [
        'scm_wcs_id',
        'ref_no',
        'date',
        'scm_warehouse_id',
        'acc_cost_center_id',
        'scm_vendor_id',
        'currency',
        'usd_to_bdt',
        'foreign_to_usd',
        'sub_total',
        'total_amount',
        'net_amount',
        'purchase_center',
        'remarks',
        'business_unit',
        'created_by',
        'closed_at',
        'closed_by',
        'is_closed',
        'closing_remarks',
        'confirmation_date',
        'confirmation_by',
        'status',
    ];

    protected $refKeyPrefix = 'WO';

    public function scmWoLines(): HasMany
    {
        return $this->hasMany(ScmWoLine::class);
    }

    public function scmWoTerms(): HasMany
    {
        return $this->hasMany(ScmWoTerm::class);
    }

    public function scmVendor(): BelongsTo
    {
        return $this->belongsTo(ScmVendor::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function scmWcs(): BelongsTo
    {
        return $this->belongsTo(ScmWcs::class);
    }

    // public function scmLcRecords(): HasMany
    // {
    //     return $this->hasMany(ScmLcRecord::class);
    // }

    // public function scmMrrs(): HasMany
    // {
    //     return $this->hasMany(ScmMrr::class);
    // }

    public function scmWoItems(): HasManyThrough
    {
        return $this->hasManyThrough(ScmWoItem::class, ScmWoLine::class);
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by' , 'id');
    }
}
