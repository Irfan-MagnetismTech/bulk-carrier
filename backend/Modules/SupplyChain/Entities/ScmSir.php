<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use App\Traits\GlobalSearchTrait;
use App\Traits\UniqueKeyGenerator;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmSi;
use Modules\SupplyChain\Traits\StockLedger;
use Modules\SupplyChain\Entities\ScmSirLine;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmSir extends Model
{
    use HasFactory, StockLedger, GlobalSearchTrait, UniqueKeyGenerator;

    protected $fillable = [
        'ref_no', 'scm_si_id', 'scm_warehouse_id', 'acc_cost_center_id', 'department_id', 'date', 'business_unit', 'created_by', 'remarks',
    ];

    protected $refKeyPrefix = 'SIR';

    public function scmSirLines(): HasMany
    {
        return $this->hasMany(ScmSirLine::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function scmSi(): BelongsTo
    {
        return $this->belongsTo(ScmSi::class);
    }
}
