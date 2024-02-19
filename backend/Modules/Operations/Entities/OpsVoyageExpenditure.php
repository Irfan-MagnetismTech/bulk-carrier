<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVoyageExpenditure extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'ops_voyage_id',
        'ops_vessel_id',
        'port_code',        
        'currency',
        'exchange_rate_usd',
        'exchange_rate_bdt',
        'sub_total_usd',
        'sub_total_bdt',
        'discount_usd',
        'discount_bdt',
        // 'grand_total_usd',
        'grand_total_bdt',
        'expense_json',
        'date',
        'type',
        'attachment',
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
    
    public function port() {
        return $this->belongsTo(OpsPort::class, 'port_code', 'code');
    }

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }
    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function opsVoyageExpenditureEntries()
    {
        return $this->hasMany(OpsVoyageExpenditureEntry::class, 'ops_voyage_expenditure_id', 'id');
    }
}
