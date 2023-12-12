<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Operations\Entities\OpsVessel;
use App\Traits\GlobalSearchTrait;

class CrwCrewRequisition extends Model
{
    use HasFactory, GlobalSearchTrait;

	protected $fillable = ['applied_date', 'ops_vessel_id', 'total_required_manpower', 'remarks', 'business_unit'];

	public function crwCrewRequisitionLines(){
		return $this->hasMany(CrwCrewRequisitionLine::class);
	}

	public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class);
    }

	public function vesselRequiredCrew(){
        return $this->belongsTo(vesselRequiredCrew::class, 'ops_vessel_id', 'ops_vessel_id');
	}
}
