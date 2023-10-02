<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwPayrollBatchHead extends Model
{
    use HasFactory;

    protected $fillable = ['payroll_batch_id', 'head_type', 'head_name', 'unit', 'based_on', 'amount'];
}
