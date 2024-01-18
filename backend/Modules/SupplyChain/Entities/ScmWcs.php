<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Accounts\Entities\AccCostCenter;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWcs extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'scm_warehouse_id',
        'acc_cost_center_id',
        'ref_no',
        'purchase_center',
        'priority',
        'special_instruction',
        'effective_date',
        'expire_date',
        'required_days',
        'remarks',
        'business_unit',
    ];

    public function scmWarehouse()
    {
        return $this->belongsTo(ScmWarehouse::class, 'scm_warehouse_id', 'id');
    }

    public function accCostCenter()
    {
        return $this->belongsTo(AccCostCenter::class, 'acc_cost_center_id', 'id');
    }

    public function scmWcsVendorServices()
    {
        return $this->hasMany(ScmWcsVendorService::class);
    }

    public function scmWcsVendors()
    {
        return $this->hasMany(ScmWcsVendor::class);
    }

    public function selectedVendors()
    {
        return $this->hasMany(ScmWcsVendor::class)->where('is_selected', true);
    }

    public function scmWcsServices(): HasMany
    {
        return $this->hasMany(ScmWcsService::class)->latest();
    }
}
