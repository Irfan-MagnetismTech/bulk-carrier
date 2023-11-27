<?php

namespace Modules\Operations\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Operations\Entities\OpsPort;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVessel extends Model
{
    use HasFactory, GlobalSearchTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'vessel_type',
        'name',
        'short_code',
        'call_sign',
        'owner_name',
        'manager',
        'classification',
        'flag',
        'port_of_registry',
        'delivery_date',
        'grt',
        'nrt',
        'dwt',
        'imo',
        'official_number',
        'keel_laying_date',
        'launching_date',
        'mmsi',
        'overall_length',
        'overall_width',
        'year_built',
        'capacity',
        'total_cargo_hold',
        'live_tracking_config',
        'remarks',
        'business_unit'
    ];




    /**
     * The accessors to append to the model's array form.
     *
     * @var string[]
     */
    protected $appends = ['code_name'];

    /**
     * Concatenate the short code and name of the port.
     *
     * @return string
     */
    public function getCodeNameAttribute()
    {
        return $this->short_code . ' - ' . $this->name;
    }

    public function opsVesselCertificates()
    {
        
        return $this->hasMany(OpsVesselCertificate::class, 'ops_vessel_id', 'id');
    }

    public function opsBunkers()
    {
        return $this->morphMany(OpsBunker::class, 'bunkerable');
    }

    public function portOfRegistry()
    {
        return $this->belongsTo(OpsPort::class, 'port_of_registry', 'code');
    }
}
