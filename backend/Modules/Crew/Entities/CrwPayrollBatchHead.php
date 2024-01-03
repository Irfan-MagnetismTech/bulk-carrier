<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwPayrollBatchHead extends Model
{
    use HasFactory;

    protected $fillable = ['crw_payroll_batch_id', 'head_type', 'head_name',  'amount', 'unit', 'based_on'];

    public function crwPayrollBatchHeadLines()
    {
        return $this->hasMany(CrwPayrollBatchHeadLine::class);
    }    
}
