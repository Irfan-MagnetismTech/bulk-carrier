<?php

namespace Modules\Crew\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppraisalForm extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['form_no', 'form_name', 'total_marks', 'version', 'description', 'business_unit'];

	public function appraisalFormLines(){
		return $this->hasMany(AppraisalFormLine::class);
	}    

}
