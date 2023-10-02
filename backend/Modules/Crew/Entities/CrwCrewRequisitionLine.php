<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrewRequisitionLine extends Model
{
    use HasFactory;

	protected $fillable = ['crw_crew_requisition_id', 'crw_rank_id', 'required_manpower', 'remarks'];
}
