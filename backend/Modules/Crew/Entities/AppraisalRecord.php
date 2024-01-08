<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppraisalRecord extends Model
{
    use HasFactory;

    protected $fillable = ['crw_crew_id', 'appraisal_form_id', 'crw_crew_assignment_id', 'appraisal_date', 'age', 'business_unit'];

	public function appraisalRecordLines(){
		return $this->hasMany(AppraisalRecordLine::class);
	}

	public function crwCrew()
    {
        return $this->belongsTo(CrwCrewProfile::class, 'crw_crew_id', 'id');
    }

	public function appraisalForm()
    {
        return $this->belongsTo(AppraisalForm::class);
    }	

	public function crwCrewAssignment()
    {
        return $this->belongsTo(CrwCrewAssignment::class);
    }	
}
