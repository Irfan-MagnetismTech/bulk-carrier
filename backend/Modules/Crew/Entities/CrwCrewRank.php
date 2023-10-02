<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrwCrewRank extends Model
{
    use HasFactory;

    protected $fillable = ['crw_crew_id', 'crw_rank_id', 'rank_name', 'effective_date'];
}
