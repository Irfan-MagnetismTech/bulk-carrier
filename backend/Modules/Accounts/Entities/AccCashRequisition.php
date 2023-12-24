<?php

namespace Modules\Accounts\Entities;

use App\Models\User;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SupplyChain\Entities\ScmPr;

class AccCashRequisition extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['acc_cost_center_id', 'applied_date', 'requisitor_id', 'scm_pr_id', 'total_amount', 'purpose', 'business_unit']; 

    public function accCashRequisitionLines()
    {
        return $this->hasMany(AccCashRequisitionLine::class);
    }

    public function costCenter()
    {
        return $this->belongsTo(AccCostCenter::class, 'acc_cost_center_id', 'id');
    }

    public function scmPr()
    {
        return $this->belongsTo(ScmPr::class, 'scm_pr_id', 'id');
    }

    public function requisitor()
    {
        return $this->belongsTo(User::class, 'requisitor_id', 'id');
    }

    
    public function accAdvanceAdjustment()
    {
        return $this->hasOne(AccAdvanceAdjustment::class);
    }
}