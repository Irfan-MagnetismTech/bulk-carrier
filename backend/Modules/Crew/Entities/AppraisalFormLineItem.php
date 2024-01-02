<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppraisalFormLineItem extends Model
{
    use HasFactory;

    protected $fillable = ['appraisal_form_line_id', 'aspect', 'description', 'answer_type'];
}
