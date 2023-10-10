<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwPayrollBatchLine extends Model
{
    use HasFactory;

    protected $fillable = ['crw_crew_id', 'crw_payroll_batch_id', 'crw_salary_structure_id', 'attendance_line_composite', 'gross_salary', 'payable_days', 'payable_amount', 'total_earnings', 'total_deductions', 'net_payable_amount', 'payment_date', 'crw_bank_account_id'];

    public function crwCrew()
    {
        return $this->belongsTo(CrwCrew::class);
    }  
}
