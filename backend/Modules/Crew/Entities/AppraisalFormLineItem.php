<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppraisalFormLineItem extends Model
{
    use HasFactory;

    protected $fillable = ['item_no', 'appraisal_form_line_id', 'aspect', 'description', 'answer_type', 'item_composite'];

	public function appraisalFormLine(){
		return $this->belongsTo(AppraisalFormLine::class);
	}
}
