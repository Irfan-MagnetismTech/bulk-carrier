<?php

namespace Modules\Crew\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operations\Entities\OpsVessel;

class AppraisalRecord extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['crw_crew_id', 'appraisal_form_id', 'crw_crew_assignment_id', 'appraisal_date', 'age', 'obtained_marks', 'business_unit'];

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
