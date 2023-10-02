<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrewAssignment extends Model
{
    use HasFactory;

	protected $fillable = ['ops_vessel_id', 'crw_crew_id', 'position_onboard', 'date_of_joining', 'port_of_joining', 'approx_duration', 'remarks', 'business_unit'];
}
