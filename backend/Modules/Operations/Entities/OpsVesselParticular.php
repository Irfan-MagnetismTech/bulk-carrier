<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVesselParticular extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'ops_vessel_id',
        'vessel_type',
        'class_no',
        'loa',
        'depth',
        'ecdis_type',
        'maker_ssas',
        'engine_type',
        'name',
        'previous_name',
        'short_code',
        'manager',
        'delivery_date',
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
        'launching_date',
        'mmsi',
        'year_built',
        'capacity',
        'total_cargo_hold',
        'tues_capacity',
        'overall_length',
        'overall_width',
        'depth_moulded',
        'bhp',
        'email',
        'lbc',
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
    

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id', 'id');
    }
}
