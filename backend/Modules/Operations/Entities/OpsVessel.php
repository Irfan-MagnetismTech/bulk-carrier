<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVessel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'code',
        'ownership',
        'flag',
        'year_built',
        'call_sign',
        'grt',
        'nrt',
        'dwt',
        'speed',
        'capacity_tues',
        'reefer_capacity',
        'imo_number',
        'classification',
        'deck_range',
        'available_stowages',
        'tier_range',
        'remarks',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Operations\Database\factories\OpsVesselFactory::new();
    }
}
