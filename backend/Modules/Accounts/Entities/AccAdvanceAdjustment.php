<?php

namespace Modules\Accounts\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccAdvanceAdjustment extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['acc_cost_center_id', 'acc_cash_requisition_id', 'adjustment_date', 'adjustment_amount', 'cash_amount', 'business_unit'];

    public function accAdvanceAdjustmentLines()
    {
        return $this->hasMany(AccAdvanceAdjustmentLine::class);
    }

    public function costCenter()
    {
        return $this->belongsTo(AccCostCenter::class, 'acc_cost_center_id', 'id');
    }

    public function accCashRequisition()
    {
        return $this->belongsTo(AccCashRequisition::class, 'acc_cash_requisition_id', 'id');
    }
}
