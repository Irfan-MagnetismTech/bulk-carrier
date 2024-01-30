<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Entities\ScmCsVendor;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Modules\SupplyChain\Entities\ScmCsMaterial;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\SupplyChain\Entities\ScmCsMaterialVendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmCs extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'ref_no',
        'scm_warehouse_id',
        'acc_cost_center_id',
        'effective_date', //date
        'expire_date',
        'special_instructions',
        'priority', //requirement type
        'required_days', //targaet days
        'purchase_center',
        'selection_ground',
        'auditor_remarks_date',
        'auditor_remarks',
        'status',
        'business_unit',
        'created_by',
        'is_foreign',
    ];


    public function scmPo(): HasMany
    {
        return $this->hasMany(ScmPo::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function scmCsVendors(): HasMany
    {
        return $this->hasMany(ScmCsVendor::class);
    }

    public function scmCsMaterialVendors(): HasMany
    {
        return $this->hasMany(ScmCsMaterialVendor::class);
    }

    public function scmCsMaterials(): HasMany
    {
        return $this->hasMany(ScmCsMaterial::class);
    }

    public function selectedVendors(): HasMany
    {
        return $this->hasMany(ScmCsVendor::class)->where('is_selected', true);
    }
}
