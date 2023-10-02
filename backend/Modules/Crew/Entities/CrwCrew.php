<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrew extends Model
{
    use HasFactory, SoftDeletes;

	protected $fillable = ['crw_crew_profile_id', 'crw_rank_id', 'name', 'email', 'contact', 'business_unit'];
}
