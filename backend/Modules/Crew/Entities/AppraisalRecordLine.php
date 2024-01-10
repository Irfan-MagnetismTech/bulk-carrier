<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppraisalRecordLine extends Model
{
    use HasFactory;

    protected $fillable = ['appraisal_record_id', 'line_composite', 'comment', 'answer'];

	public function appraisalFormLine(){
		return $this->belongsTo(AppraisalFormLine::class, 'line_composite', 'line_composite');
	}

	public function appraisalRecordLineItems(){
		return $this->hasMany(AppraisalRecordLineItem::class, 'appraisal_record_line_id', 'id');
	}	
}
