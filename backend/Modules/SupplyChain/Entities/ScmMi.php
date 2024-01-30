<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use App\Traits\GlobalSearchTrait;
use App\Traits\UniqueKeyGenerator;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMmr;
use Modules\SupplyChain\Entities\ScmMiLine;
use Modules\SupplyChain\Traits\StockLedger;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Modules\SupplyChain\Entities\ScmMiShortage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmMi extends Model
{
    use HasFactory, StockLedger, GlobalSearchTrait, UniqueKeyGenerator;
    
    protected $fillable = [
        'ref_no', 'scm_mmr_id','scm_mo_id', 'from_warehouse_id', 'to_warehouse_id', 'date', 'business_unit', 'created_by',
    ];

    protected $refKeyPrefix = 'MI';

    public function scmMiLines(): HasMany
    {
        return $this->hasMany(ScmMiLine::class);
    }

    public function scmMiShortage(){
        return $this->hasOne(ScmMiShortage::class);
    }
    
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

    public function scmMmr(): BelongsTo
    {
        return $this->belongsTo(ScmMmr::class);
    }

    public function scmMo(): BelongsTo
    {
        return $this->belongsTo(ScmMo::class);
    }
}
