<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMaterial;
use App\Traits\GlobalSearchTrait;

class AccFixedAsset extends Model
{
    use HasFactory, GlobalSearchTrait;

    /**
     * @var array
     */
    protected $fillable = ['acc_cost_center_id', 'scm_mrr_id', 'scm_material_id', 'brand', 'model', 'serial', 'acc_parent_account_id', 'acc_account_id', 'asset_tag', 'location', 'acquisition_date', 'useful_life', 'depreciation_rate', 'acquisition_cost', 'business_unit'];

    /**
     * @return mixed
     */
    public function fixedAssetCosts()
    {
        return $this->hasMany(AccFixedAssetCost::class);
    }

    public function account()
    {
        return $this->hasOne(AccAccount::class, 'id', 'acc_account_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(AccCostCenter::class, 'acc_cost_center_id', 'id');
    }

    public function scmMrr()
    {
        return $this->belongsTo(ScmMaterial::class, 'scm_mrr_id', 'id');
    }

    public function scmMaterial()
    {
        return $this->belongsTo(ScmMaterial::class, 'scm_material_id', 'id');
    }

    public function fixedAssetCategory()
    {
        return $this->belongsTo(AccAccount::class, 'acc_parent_account_id', 'id');
    }
}
