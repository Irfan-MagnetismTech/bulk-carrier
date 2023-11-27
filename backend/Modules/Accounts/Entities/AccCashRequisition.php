<?php

namespace Modules\Accounts\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccCashRequisition extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['acc_cost_center_id', 'applied_date', 'requisitor_id', 'mpr_id', 'total_amount', 'purpose', 'business_unit']; 

    public function accCashRequisitionLines()
    {
        return $this->hasMany(AccCashRequisitionLine::class);
    }

    public function costCenter()
    {
        return $this->belongsTo(AccCostCenter::class, 'acc_cost_center_id', 'id');
    }
}