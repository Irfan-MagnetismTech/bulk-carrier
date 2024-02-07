<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Crew\Entities\CrwCrewEducation;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrwCrewProfile extends Model
{
    use HasFactory, GlobalSearchTrait;

	protected $fillable = ['crw_recruitment_approval_id', 'hired_by', 'agency_id', 'department_name', 'crw_rank_id', 'employee_type', 'is_officer', 'first_name', 'last_name', 'full_name', 'father_name', 'mother_name', 'date_of_birth', 'gender', 'religion', 'marital_status', 'nationality', 'nid_no', 'passport_no', 'passport_issue_date', 'blood_group', 'height', 'weight', 'pre_address', 'pre_city', 'pre_mobile_no', 'pre_email', 'per_address', 'per_city', 'per_mobile_no', 'per_email', 'picture', 'attachment', 'business_unit'];

    protected $with = ['crwCurrentRank']; 
    

    public function crwRank()
    {
        return $this->belongsTo(CrwRank::class);
    }

    public function crwCurrentRank()
    {
        return $this->belongsTo(CrwRank::class, 'crw_rank_id', 'id');
    }

    public function crewRecruitmentApproval(){
        return $this->belongsTo(CrwRecruitmentApproval::class);
    }

    public function crewAgency(){
        return $this->belongsTo(CrwAgency::class);
    }

    public function educations(){
		return $this->hasMany(CrwCrewEducation::class);
	}

    public function trainings(){
		return $this->hasMany(CrwCrewTraining::class);
	}

    public function experiences(){
		return $this->hasMany(CrwCrewExperience::class);
	}

    public function languages(){
		return $this->hasMany(CrwCrewLanguage::class);
	}

    public function references(){
		return $this->hasMany(CrwCrewReference::class);
	}

    public function nominees(){
		return $this->hasMany(CrwCrewNominee::class);
	}


    public function crewDocuments()
    {
        return $this->hasMany(CrwCrewDocument::class);
    }


}
