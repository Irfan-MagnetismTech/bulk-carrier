<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwPayrollBatchHeadLine extends Model
{
    use HasFactory;

    protected $fillable = ['payroll_batch_line_id','payroll_batch_head_id','crw_crew_id','particular','amount'];
}
