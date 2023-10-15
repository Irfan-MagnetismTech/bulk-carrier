<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrewExperience extends Model
{
    use HasFactory;

    protected $fillable = ['employer_name','from_date','till_date','last_designation','reason_for_leave'];
}
