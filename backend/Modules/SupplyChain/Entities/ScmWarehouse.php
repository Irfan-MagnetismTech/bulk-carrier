<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SupplyChain\Entities\ScmWarehouseContactPerson;

class ScmWarehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'cost_center_id', 'address', 'short_code', 'ops_vessel_id', 'business_unit',
    ];

    public function scmWarehouseContactPersons(): HasMany
    {
        return $this->hasMany(ScmWarehouseContactPerson::class)->latest();
    }
}
