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

	protected $fillable = ['ops_vessel_id', 'assignment_code', 'crw_crew_id', 'position_onboard', 'joining_date', 'joining_port_code', 'duration', 'status', 'completion_date', 'completion_remarks', 'remarks', 'business_unit'];

	public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class);
    }

    public function crwCrew()
    {
        return $this->belongsTo(CrwCrewProfile::class, 'crw_crew_id', 'id');
    }

    public function opsPort()
    {
        return $this->hasOne(OpsPort::class,'code','joining_port_code');
    }
}
