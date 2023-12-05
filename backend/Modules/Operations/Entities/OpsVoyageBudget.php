<?php

namespace Modules\Operations\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVoyageBudget extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'ops_vessel_id',
        'ops_voyage_id',
        'ops_expense_head_id',
        'title',
        'total',
        'business_unit',
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
