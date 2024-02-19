<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVoyageBudget extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'ops_vessel_id',
        'ops_voyage_id',
        'ops_expense_head_id',
        'title',
        'total',
        'effective_from',
        'effective_till',
        'business_unit',
        'currency',
        'exchange_rate_usd',
        'exchange_rate_bdt',
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

    public function opsVoyageBudgetEntries()
    {
        return $this->hasMany(OpsVoyageBudgetEntry::class, 'ops_voyage_budget_id', 'id');
    }
}
