<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwPayrollBatchHeadLine extends Model
{
    use HasFactory;

    protected $fillable = ['crw_payroll_batch_id','crw_payroll_batch_head_id','crw_crew_id', 'head_type', 'amount'];

    public function crwCrewProfile()
    {
        return $this->belongsTo(CrwCrewProfile::class, 'crw_crew_id', 'id');
    }  
}
