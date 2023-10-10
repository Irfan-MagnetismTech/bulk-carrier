<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrewRequisition extends Model
{
    use HasFactory;

	protected $fillable = ['applied_date', 'ops_vessel_id', 'total_required_manpower', 'remarks', 'business_unit'];

	public function crwCrewRequisitionLines(){
		return $this->hasMany(CrwCrewRequisitionLine::class);
	}

	public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class);
    }	
}
