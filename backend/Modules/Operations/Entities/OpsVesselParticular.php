<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsVesselParticular extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_vessel_id',
        'vessel_type',
        'depth',
        'class_no'
        'attachment',
        'loa',
        'depth',
        'ecdis_type',
        'maker_ssas',
        'engine_type',
        'bhp',
        'email',
        'lbc'
        'previous_name',
        'call_sign',
        'owner_name',
        'classification',
        'flag',
        'previous_flag',
        'port_of_registry',
        'nrt',
        'dwt',
        'imo',
        'grt',
        'official_number',
        'keel_laying_date',
        'mmsi',
        'year_built',
        'tues_capacity',
        'overall_length',
        'overall_width',
        'depth_moulded',
        'business_unit',
    ];

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }
}
