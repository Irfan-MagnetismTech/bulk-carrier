<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwVesselRequiredCrew extends Model
{
    use HasFactory;

	protected $fillable = ['ops_vessel_id', 'total_crew', 'effective_date', 'remarks', 'business_unit'];

	public function crwVesselRequiredCrewLines(){
		return $this->hasMany(CrwVesselRequiredCrewLine::class);
	}

	public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class);
    }
}
