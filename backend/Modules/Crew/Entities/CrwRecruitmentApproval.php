<?php

namespace Modules\Crew\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrwRecruitmentApproval extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

	protected $fillable = ['applied_date', 'page_title', 'subject', 'total_approved', 'crew_agreed_to_join', 'crew_selected', 'crew_panel', 'crew_rest', 'body', 'remarks', 'business_unit'];

	public function crwRecruitmentApprovalLines(){
		return $this->hasMany(CrwRecruitmentApprovalLine::class);
	}

    public function crwCrewProfiles(): HasMany
    {
        return $this->hasMany(CrwCrewProfile::class, 'crw_recruitment_approval_id', 'id');
    }	
}