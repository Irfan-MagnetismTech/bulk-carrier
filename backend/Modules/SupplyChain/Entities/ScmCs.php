<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Entities\ScmCsVendor;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Modules\SupplyChain\Entities\ScmCsMaterial;
use Modules\SupplyChain\Entities\ScmCsMaterialVendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmCs extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'ref_no',
        'scm_warehouse_id',
        'acc_cost_center_id',
        'effective_date',
        'expire_date',
        'special_instructions',
        'priority',
        'required_days',
        'purchase_center',
        'selection_ground',
        'auditor_remarks_date',
        'auditor_remarks',
        'status',
        'business_unit',
        'created_by',
        'is_foreign',
    ];


    public function scmPr()
    {
        return $this->belongsTo(ScmPr::class);
    }

    public function scmWarehouse()
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function scmCsVendors()
    {
        return $this->hasMany(ScmCsVendor::class);
    }

    public function scmCsMaterialVendors()
    {
        return $this->hasMany(ScmCsMaterialVendor::class);
    }

    public function scmCsMaterials()
    {
        return $this->hasMany(ScmCsMaterial::class);
    }
}
