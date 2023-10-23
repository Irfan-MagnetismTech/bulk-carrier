<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVoyageBudgetEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_vessel_id',
        'ops_voyage_id',
        'ops_voyage_budget_id',
        'ops_expense_head_id',
        'perticular',
        'percentage',
        'currency',
        'amount',
        'exchange_rate_bdt',
        'exchange_rate_usd',
        'amount_bdt',
        'amount_usd',
        'remarks',
        'business_unit',
    ];


    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function opsExpenseHead()
    {
        return $this->belongsTo(OpsExpenseHead::class, 'ops_expense_head_id' , 'id');
    }
    public function opsExpenseHead()
    {
        return $this->belongsTo(OpsVoyageBudget::class);
    }
}
