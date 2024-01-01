<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwPayrollBatchLine extends Model
{
    use HasFactory;

    protected $fillable = ['crw_payroll_batch_id', 'crw_crew_id', 'crw_attendance_line_id', 'crw_salary_structure_id', 'payable_amount', 'total_earnings', 'total_deductions', 'net_payable_amount', 'payment_date', 'crw_bank_account_id'];

    public function crwCrew()
    {
        return $this->belongsTo(CrwCrewProfile::class);
    }

    public function crwSalaryStructure()
    {
        return $this->belongsTo(CrwSalaryStructure::class);
    }

    public function crwAttendanceLine()
    {
        return $this->belongsTo(CrwAttendanceLine::class);
    }
}
