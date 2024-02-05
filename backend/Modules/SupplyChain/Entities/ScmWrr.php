<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ScmWrr extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'ref_no',
        'type',,
        'date',
        'scm_wo_id',
        // 'scm_wr_id',
        'scm_warehouse_id',
        // 'scm_wcs_id',
        'acc_cost_center_id',
        'remarks',
        'challan_no',
        'is_qc_passed',
        'qc_remarks',
        'business_unit',
        'created_by',
        'is_completed',
        'purchase_center',
    ];

    public function scmWrrLines(): HasMany
    {
        return $this->hasMany(ScmWrrLine::class);
    }

    public function scmWo(): BelongsTo
    {
        return $this->belongsTo(ScmWo::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function scmWrrLineItems(): HasManyThrough
    {
        return $this->hasManyThrough(ScmWrrItem::class, ScmWrrLine::class);
    }
}
