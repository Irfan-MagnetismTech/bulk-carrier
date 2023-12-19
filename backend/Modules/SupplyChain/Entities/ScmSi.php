<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmSr;
use Modules\SupplyChain\Entities\ScmSir;
use Modules\SupplyChain\Entities\ScmSiLine;
use Modules\SupplyChain\Traits\StockLedger;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmSi extends Model
{
    use HasFactory, StockLedger, GlobalSearchTrait;

    protected $fillable = [
        'ref_no', 'scm_warehouse_id', 'acc_cost_center_id', 'scm_sr_id', 'department_id', 'date', 'remarks', 'business_unit', 'created_by',
    ];

    public function scmSiLines(): HasMany
    {
        return $this->hasMany(ScmSiLine::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function scmSr(): BelongsTo
    {
        return $this->belongsTo(ScmSr::class);
    }

    public function scmSirs(): HasMany
    {
        return $this->hasMany(ScmSir::class);
    }
}
