<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwIncident extends Model
{
    use HasFactory;

	protected $fillable = ['ops_vessel_id', 'date_time', 'type', 'location', 'attachment', 'reported_by', 'description', 'business_unit'];

	public function crwIncidentParticipants(){
		return $this->hasMany(CrwIncidentParticipant::class);
	}

	public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class);
    }	
}
