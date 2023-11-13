<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrewEducation extends Model
{
    use HasFactory;
    protected $table = 'crw_crew_educations'; 

	protected $fillable = ['exam_title', 'major', 'institute', 'result', 'passing_year', 'duration', 'achievement'];
}
