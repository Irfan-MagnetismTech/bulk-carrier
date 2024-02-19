<?php

namespace Modules\Crew\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppraisalForm extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = ['form_no', 'form_name', 'total_marks', 'version', 'description', 'business_unit'];
	
    protected $skipForDeletionCheck = ['appraisalFormLines'];

    /**
     * @var array
     */
    protected $features = [
        'appraisalRecords' => 'Appraisal Records',
    ];

    /* ------------------------- Associate Relationship Start ------------------------- */
	public function appraisalFormLines() : HasMany
    {
		return $this->hasMany(AppraisalFormLine::class);
	}
    /* ------------------------- Associate Relationship End ------------------------- */

    /* -------------------------  Belongs Relationship Start ------------------------- */
    /* -------------------------  Belongs Relationship End ------------------------- */
	
	/* -------------------------  Has Many Relationship Start ------------------------- */
	public function appraisalRecords() : HasMany
    {
		return $this->hasMany(AppraisalRecord::class, 'appraisal_form_id', 'id');
	}	
	/* -------------------------  Has Many Relationship End ------------------------- */
}
