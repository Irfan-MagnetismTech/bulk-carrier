<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccFixedAsset extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['acc_cost_center_id', 'department_id', 'acc_account_id', 'cr_account_id', 'acc_material_id', 'received_date', 'tag', 'mrr_no', 'bill_no', 'name', 'life_time', 'brand', 'asset_type', 'location', 'model', 'serial', 'price', 'percentage', 'use_date', 'business_unit'];

    /**
     * @return mixed
     */
    public function fixedAssetCosts()
    {
        return $this->hasMany(AccFixedAssetCost::class);
    }
}
