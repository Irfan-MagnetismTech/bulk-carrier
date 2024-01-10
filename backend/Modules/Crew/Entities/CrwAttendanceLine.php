<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwAttendanceLine extends Model
{
    use HasFactory;

	protected $fillable = ['crw_crew_id', 'crw_crew_assignment_id', 'attendance_line_composite', 'present_days', 'absent_days', 'payable_days'];

    public function crwCrew()
    {
        return $this->belongsTo(CrwCrewProfile::class, 'crw_crew_id', 'id');
    }

    public function crwCrewAssignment()
    {
        return $this->belongsTo(CrwCrewAssignment::class);
    }

    public function crwSalaryStructure()
    {
        return $this->belongsTo(CrwSalaryStructure::class, 'crw_crew_id', 'crw_crew_id');
    }
}
