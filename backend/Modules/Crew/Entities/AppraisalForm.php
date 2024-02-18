<?php

namespace Modules\Crew\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppraisalForm extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = ['form_no', 'form_name', 'total_marks', 'version', 'description', 'business_unit'];
	
    protected $skipForDeletionCheck = ['appraisalFormLines'];

    /**
     * @var array
     */
    protected $features = [
        'appraisalRecords' => 'Rest Hour',
    ];

    /* ------------------------- Associate Relationship Start ------------------------- */
	public function appraisalFormLines(){
		return $this->hasMany(AppraisalFormLine::class);
	}
    /* ------------------------- Associate Relationship End ------------------------- */

    /* -------------------------  Belongs Relationship Start ------------------------- */
    /* -------------------------  Belongs Relationship End ------------------------- */
	
	/* -------------------------  Has Many Relationship Start ------------------------- */
	public function appraisalRecords(){
		return $this->hasMany(AppraisalRecord::class, 'appraisal_form_id', 'id');
	}	
	/* -------------------------  Has Many Relationship End ------------------------- */
}
