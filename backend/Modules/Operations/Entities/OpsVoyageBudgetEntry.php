<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVoyageBudgetEntry extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_vessel_id',
        'ops_voyage_id',
        'ops_voyage_budget_id',
        'ops_expense_head_id',
        'perticular', // not needed
        'percentage', // not needed
        'type',
        'quantity',
        'rate',
        'currency',
        'amount',
        'exchange_rate_bdt',
        'exchange_rate_usd',
        'amount_bdt',
        'amount_usd',
        'remarks',
        'business_unit',
    ];


    /**
    * @var array
    */
    protected $skipForDeletionCheck = [''];
    
    /**
    * @var array
    */
    protected $features = [
    // 'relationName'           => 'Menu',
    ];
    
    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class);
    }

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class);
    }

    public function opsExpenseHead()
    {
        return $this->belongsTo(OpsExpenseHead::class);
    }
    public function opsVoyageBudget()
    {
        return $this->belongsTo(OpsVoyageBudget::class);
    }
}
