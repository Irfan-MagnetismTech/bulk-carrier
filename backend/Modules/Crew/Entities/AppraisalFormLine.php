<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppraisalFormLine extends Model
{
    use HasFactory;

    protected $fillable = ['line', 'section_no', 'section_name', 'is_tabular', 'line_composite'];

	public function appraisalFormLineItems(){
		return $this->hasMany(AppraisalFormLineItem::class);
	}

	public function appraisalRecordLine(){
		return $this->hasOne(AppraisalRecordLine::class, 'line_composite', 'line_composite');
	}    
}