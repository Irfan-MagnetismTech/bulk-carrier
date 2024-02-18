<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use App\Traits\UniqueKeyGenerator;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmPo;
use Modules\SupplyChain\Entities\ScmMrrLine;
use Modules\SupplyChain\Entities\ScmLcRecord;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Modules\SupplyChain\Entities\ScmStockLedger;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SupplyChain\Entities\ScmCostingAllocation;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ScmMrr extends Model
{
    use HasFactory, GlobalSearchTrait, UniqueKeyGenerator;

    protected $fillable = [
        'ref_no',
        'type',
        'date',
        'scm_po_id',
        'scm_warehouse_id',
        'scm_lc_record_id',
        'acc_cost_center_id',
        'remarks',
        'challan_no',
        'is_qc_passed',
        'qc_remarks',
        'business_unit',
        'created_by',
        'is_completed',
        'purchase_center',
        'total_value',
        'total_net_value',
    ];

    protected $refKeyPrefix = 'MRR';

    public function scmMrrLines(): HasMany
    {
        return $this->hasMany(ScmMrrLine::class);
    }

    public function scmPo(): BelongsTo
    {
        return $this->belongsTo(ScmPo::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function scmLcRecord(): BelongsTo
    {
        return $this->belongsTo(ScmLcRecord::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function stockable()
    {
        return $this->morphMany(ScmStockLedger::class, 'stockable');
    }

    public function scmMrrLineItems(): HasManyThrough
    {
        return $this->hasManyThrough(ScmMrrLineItem::class, ScmMrrLine::class);
    }

    public function scmCostingAllocations(): HasMany
    {
        return $this->hasMany(ScmCostingAllocation::class, 'scm_mrr_id', 'id');
    }

//     public function scopeWithStockableChild($query)
// {
    // return $query->addSelect(['has_stockable_child' => ScmStockLedger::selectRaw('COUNT(*)')
    //     ->whereColumn('stockable_id', 'scm_mrrs.id')
    //     ->where('stockable_type', 'Modules\SupplyChain\Entities\ScmMrr')
    //     ->whereHas('child')
    // ]);
// }
public function scopeWithStockableChild($query)
{
    return $query->addSelect(['has_stockable_child' => ScmStockLedger::selectRaw('COUNT(*)')
        ->whereColumn('stockable_id', 'scm_mrrs.id')
        ->where('stockable_type', 'Modules\SupplyChain\Entities\ScmMrr')
        ->whereHas('child')
    ]);
}
}
