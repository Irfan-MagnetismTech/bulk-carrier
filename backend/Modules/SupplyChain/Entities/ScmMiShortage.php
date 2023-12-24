<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMi;
use Modules\SupplyChain\Traits\StockLedger;
use Modules\Accounts\Entities\AccCostCenter;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Modules\SupplyChain\Entities\ScmMiShortageLine;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmMiShortage extends Model
{
    use HasFactory, StockLedger;

    protected $fillable = [
        'scm_mi_id',
        'shortage_type',
        'scm_warehouse_id',
        'scm_cost_center_id',
        'business_unit'
    ];

    public function scmMiShortageLines()
    {
        return $this->hasMany(ScmMiShortageLine::class);
    }

    public function scmMi()
    {
        return $this->belongsTo(ScmMi::class);
    }

    public function scmWarehouse()
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function costCenter()
    {
        return $this->belongsTo(AccCostCenter::class);
    }

}
