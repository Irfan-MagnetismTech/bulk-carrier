<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppraisalRecordLine extends Model
{
    use HasFactory;

    protected $fillable = ['appraisal_record_id', 'line_item_composite', 'comment', 'answer'];
}
