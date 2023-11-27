<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmSrLine;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmSr extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'ref_no', 'scm_warehouse_id', 'acc_cost_center_id', 'department_id', 'date', 'remarks', 'business_unit', 'created_by',
    ];

    public function scmSrLines(): HasMany
    {
        return $this->hasMany(ScmSrLine::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
