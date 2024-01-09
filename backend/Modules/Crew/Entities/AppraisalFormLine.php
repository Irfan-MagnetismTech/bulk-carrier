<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppraisalFormLine extends Model
{
    use HasFactory;

    protected $fillable = ['line', 'section_no', 'section_name', 'is_tabular'];

	public function appraisalFormLineItems(){
		return $this->hasMany(AppraisalFormLineItem::class);
	}    
}
