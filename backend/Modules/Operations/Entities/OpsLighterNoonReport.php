<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreateBusinessUnit;

class OpsLighterNoonReport extends Model
{
    use HasFactory, CreateBusinessUnit;

    protected $fillable = [
        'ops_vessel_id',
        'ops_voyage_id',
        'ship_master',
        'chief_engineer',
        'noon_position',
        'status',
        'engine_running_hours',
        'date',
        'last_port',
        'next_port',
        'business_unit',
        'remarks',
    ];


    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function opsBunkers()
    {
        return $this->morphMany(OpsBunker::class, 'bunkerable');
    }
}
