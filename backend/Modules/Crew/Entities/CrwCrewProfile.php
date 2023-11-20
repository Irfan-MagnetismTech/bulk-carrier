<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Crew\Entities\CrwCrewEducation;
use App\Traits\GlobalSearchTrait;

class CrwCrewProfile extends Model
{
    use HasFactory, GlobalSearchTrait;

	protected $fillable = ['crw_recruitment_approval_id', 'hired_by', 'agency_id', 'department_id', 'rank_id', 'first_name', 'last_name', 'father_name', 'mother_name', 'date_of_birth', 'gender', 'religion', 'marital_status', 'nationality', 'nid_no', 'passport_no', 'passport_issue_date', 'blood_group', 'height', 'weight', 'pre_address', 'pre_city', 'pre_mobile_no', 'pre_email', 'per_address', 'per_city', 'per_mobile_no', 'per_email', 'picture', 'attachment', 'business_unit'];

    public function crewBasicInfo(){
        return $this->hasOne(CrwCrew::class, 'crw_crew_profile_id', 'id');
    }

    public function crewRank(){
        return $this->hasOne(CrwRank::class, 'id', 'rank_id');
    }

    public function crewRecruitmentApproval(){
        return $this->hasOne(CrwRecruitmentApproval::class, 'id', 'crw_recruitment_approval_id');
    }

    public function crewAgency(){
        return $this->hasOne(CrwAgency::class, 'id', 'agency_id');
    }

    public function educations(){
		return $this->hasMany(CrwCrewEducation::class, 'crw_crew_profile_id', 'id');
	}

    public function trainings(){
		return $this->hasMany(CrwCrewTraining::class, 'crw_crew_profile_id', 'id');
	}

    public function experiences(){
		return $this->hasMany(CrwCrewExperience::class, 'crw_crew_profile_id', 'id');
	}

    public function languages(){
		return $this->hasMany(CrwCrewLanguage::class, 'crw_crew_profile_id', 'id');
	}

    public function references(){
		return $this->hasMany(CrwCrewReference::class, 'crw_crew_profile_id', 'id');
	}

    public function nominees(){
		return $this->hasMany(CrwCrewNominee::class, 'crw_crew_profile_id', 'id');
	}

}
