<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppraisalFormLine extends Model
{
    use HasFactory;

    protected $fillable = ['appraisal_form_id', 'section_name', 'aspect', 'description', 'answer_type'];
}
