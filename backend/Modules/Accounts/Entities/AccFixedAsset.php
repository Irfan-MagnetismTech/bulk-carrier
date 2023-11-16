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
    protected $fillable = ['acc_cost_center_id', 'department_id', 'acc_account_id', 'cr_account_id', 'acc_material_id', 'received_date', 'asset_name', 'asset_type', 'tag', 'mrr_no', 'bill_no', 'brand', 'location', 'model', 'serial', 'price', 'acquisition_cost', 'useful_life', 'acquisition_date', 'percentage', 'business_unit'];

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
        return $this->hasOne(AccCostCenter::class, 'id', 'acc_cost_center_id');
    }

    public function scmMaterial()
    {
        return $this->belongsTo(ScmMaterial::class, 'acc_material_id', 'id');
    }
}
