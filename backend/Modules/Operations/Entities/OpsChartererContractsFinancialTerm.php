<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsChartererContractsFinancialTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_charterer_contract_id',
        'ops_voyage_id',
        'ops_cargo_type_id',
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

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function opsCargoType()
    {
        return $this->belongsTo(OpsCargoType::class, 'ops_cargo_type_id' , 'id');
    }
}
