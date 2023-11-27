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
        'ref_no', 'date', 'delivery_date', 'from_warehouse_id', 'to_warehouse_id', 'requested_by', 'requested_for', 'remarks', 'business_unit', 'created_by',
    ];

    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class, 'from_warehouse_id', 'id');
    }

    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class, 'to_warehouse_id', 'id');
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
