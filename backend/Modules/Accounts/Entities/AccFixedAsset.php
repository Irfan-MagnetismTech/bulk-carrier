<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMaterial;
use App\Traits\GlobalSearchTrait;
use Modules\SupplyChain\Entities\ScmMrr;

class AccFixedAsset extends Model
{
    use HasFactory, GlobalSearchTrait;

    /**
     * @var array
     */
    protected $fillable = ['acc_cost_center_id', 'scm_mrr_id', 'scm_material_id', 'brand', 'model', 'serial', 'acc_parent_account_id', 'acc_account_id', 'asset_tag', 'location', 'acquisition_date', 'useful_life', 'depreciation_rate', 'acquisition_cost', 'business_unit'];

    protected $appends = ['left_days']; 


    public function getLeftDaysAttribute()
    {
        // $expireDate = Carbon::parse($this->expire_date);
        // $today = Carbon::today();

        // return $today->diffInDays($expireDate, false);
    }    

    /**
     * @return mixed
     */
    public function fixedAssetCosts()
    {
        return $this->hasMany(AccFixedAssetCost::class);
    }

    public function transaction()
    {
        return $this->morphOne(AccTransaction::class,'transactionable')->withDefault();
    }

    public function morphaccount()
    {
        return $this->morphMany(AccAccount::class,'accountable');
    }

    public function account()
    {
        return $this->belongsTo(AccAccount::class, 'acc_account_id','id');
    }

    public function fixedAssetAccount (){
        return $this->morphOne(AccAccount::class,'accountable')->where('acc_balance_and_income_line_id', config('accounts.balance_income_line.fixed_assets_at_cost'));
    }

    public function depreciationAccount(){
        return $this->morphOne(AccAccount::class, 'accountable')->where('acc_balance_and_income_line_id', config('accounts.balance_income_line.acumulated_depreciation'));
    }

    public function costCenter()
    {
        return $this->belongsTo(AccCostCenter::class, 'acc_cost_center_id', 'id');
    }

    public function scmMrr()
    {
        return $this->belongsTo(ScmMrr::class, 'scm_mrr_id', 'id');
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
