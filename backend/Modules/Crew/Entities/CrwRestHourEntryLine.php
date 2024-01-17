<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CrwRestHourEntryLine extends Model
{
    use HasFactory;

    protected $fillable = ['crw_rest_hour_entry_id', 'crw_crew_id', 'crw_crew_assignment_id', 'work_hours', 'rest_hours', 'overtime_hours', 'applicable_rest_hour_daily', 'applicable_rest_hour_weekly', 'comments', 'hourly_records'];

    public function crwCrew()
    {
        return $this->belongsTo(CrwCrewProfile::class, 'crw_crew_id', 'id');
    }    

    public function crwCrewAssignment()
    {
        return $this->belongsTo(CrwCrewAssignment::class);
    }    
}
