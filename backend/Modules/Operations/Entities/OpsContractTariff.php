<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsContractTariff extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_vessel_id',
        'ops_voyage_id',
        'ops_cargo_tariff_id',
        'ops_voyage_sector_id',
        'loading_point',
        'unloading_point',
        'tariff_month',
        'quantity',
        'total_rate',
        'pol_pod'
    ];

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function opsCargoTariff()
    {
        return $this->belongsTo(OpsCargoTariff::class, 'ops_cargo_tariff_id' , 'id');
    }

    public function opsVoyageSectors()
    {
        return $this->hasMany(OpsVoyageSector::class);
    }

}
