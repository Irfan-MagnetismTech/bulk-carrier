<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVoyageExpenditure extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_voyage_id',
        'port',
        'total_usd',
        'total_bdt',
        'expense_json',
        'date',
        'type',
        'business_unit',
    ];

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function opsVoyageExpenditureEntries()
    {
        return $this->hasMany(OpsVoyageExpenditureEntry::class, 'ops_voyage_expenditure_id', 'id');
    }
}
