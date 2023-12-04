<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Operations\Entities\OpsPort;
use Modules\Operations\Entities\OpsVessel;
use App\Traits\GlobalSearchTrait;

class CrwCrewAssignment extends Model
{
    use HasFactory, GlobalSearchTrait;

	protected $fillable = ['ops_vessel_id', 'crw_crew_id', 'position_onboard', 'date_of_joining', 'port_of_joining', 'approx_duration', 'remarks', 'business_unit'];

	public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class);
    }

    public function crwCrew()
    {
        return $this->belongsTo(CrwCrewProfile::class, 'crw_crew_id', 'id');
    }

    public function port()
    {
        return $this->hasOne(OpsPort::class,'code','port_of_joining');
    }
}
