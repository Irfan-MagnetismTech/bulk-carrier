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

	public function appraisalFormLineItem(){
		return $this->belongsTo(AppraisalFormLineItem::class, 'line_item_composite', 'line_item_composite');
	}
}
