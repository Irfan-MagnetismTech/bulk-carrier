<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrewChecklistLine extends Model
{
    use HasFactory;

	protected $fillable = ['crw_crew_checklist_id', 'item_name', 'remarks'];
}
