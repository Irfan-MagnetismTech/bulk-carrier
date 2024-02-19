<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsChartererContractsFinancialTerm extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_charterer_contract_id',
        'ops_voyage_id',
        'ops_cargo_tariff_id',
        'credit_days',
        'billing_cycle',
        'valid_from',
        'valid_till',
        'status',
        'approximate_load_amount',
        'per_mt_charge',
        'per_day_charge',
        'cleaning_fee',
        'cancellation_fee',
        'others_fee',
        'per_ton_charge',
        'bunker_provider',
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
    
    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function opsCargoTariff()
    {
        return $this->belongsTo(OpsCargoTariff::class, 'ops_cargo_tariff_id' , 'id');
    }
}
