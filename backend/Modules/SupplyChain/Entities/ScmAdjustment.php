<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmAdjustmentLine;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmAdjustment extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'ref_no', 'date', 'scm_warehouse_id','acc_cost_center_id', 'remarks', 'business_unit', 'created_by', 'type'
    ];

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class, 'scm_warehouse_id', 'id');
    }

    public function createdBy(): BelongsTo  
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function scmAdjustmentLines(): HasMany
    {
        return $this->hasMany(ScmAdjustmentLine::class);
    }

}
