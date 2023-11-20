<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Accounts\Entities\AccCostCenter;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SupplyChain\Entities\ScmWarehouseContactPerson;

class ScmWarehouse extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'name', 'cost_center_id', 'address', 'short_code', 'ops_vessel_id', 'business_unit',
    ];

    public function scmWarehouseContactPersons(): HasMany
    {
        return $this->hasMany(ScmWarehouseContactPerson::class)->latest();
    }

    public function accCostCenter(): BelongsTo
    {
        return $this->belongsTo(AccCostCenter::class, 'cost_center_id');
    }
    
    public function scmWarehouseContactPerson(): HasOne
    {
        return $this->hasOne(ScmWarehouseContactPerson::class)->latest();
    }
}
