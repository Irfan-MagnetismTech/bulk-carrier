<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwRecruitmentApproval extends Model
{
    use HasFactory;

	protected $fillable = ['applied_date', 'page_title', 'subject', 'total_approved', 'crew_agreed_to_join', 'crew_selected', 'crew_panel', 'crew_rest', 'body', 'remarks', 'business_unit'];

	public function crwRecruitmentApprovalLines(){
		return $this->hasMany(CrwRecruitmentApprovalLine::class);
	}
}