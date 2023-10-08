<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsVoyage extends Model
{
    use HasFactory;
    protected $fillable = [
        'ops_customer_id',
        'ops_vessel_id',
        'mother_vessel_id',
        'ops_cargo_type_id',
        'voyage_no',
        'route',
        'load_port_distance',
        'sail_date',
        'transit_date',
        'remarks',
        'business_unit'
    ];

    public function ops_customer()
    {
        return $this->belongsTo(OpsCustomer::class, 'ops_customer_id' , 'id');
    }

    public function ops_vessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function ops_mother_vessel()
    {
        return $this->belongsTo(OpsVessel::class, 'mother_vessel_id' , 'id');
    }

    public function ops_cargo_type()
    {
        return $this->belongsTo(OpsCargoType::class, 'ops_voyage_id' , 'id');
    }

    public function ops_voyage_sectors()
    {
        return $this->hasMany(OpsVoyageSector::class, 'ops_cargo_tariff_id', 'id');
    }

    public function ops_voyage_port_schedules()
    {
        return $this->hasMany(OpsVoyagePortSchedule::class, 'ops_cargo_tariff_id', 'id');
    }

    public function ops_bunkers()
    {
        return $this->morphMany(OpsBunker::class, 'bunkerable');
    }
}
