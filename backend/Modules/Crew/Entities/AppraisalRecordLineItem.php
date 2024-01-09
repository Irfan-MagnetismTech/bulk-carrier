<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppraisalRecordLineItem extends Model
{
    use HasFactory;

    protected $fillable = ['appraisal_record_id', 'item_composite', 'comment', 'answer'];

	public function appraisalFormLineItem(){
		return $this->belongsTo(AppraisalFormLineItem::class, 'item_composite', 'item_composite');
	}
}
