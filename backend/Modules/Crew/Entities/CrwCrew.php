<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CrwCrew extends Model
{
    use HasFactory;

	protected $fillable = ['crw_crew_profile_id', 'crw_rank_id', 'name', 'email', 'contact', 'business_unit'];

    public function crwRank()
    {
        return $this->belongsTo(CrwRank::class);
    }

    public function crwCrewProfile()
    {
        return $this->belongsTo(CrwCrewProfile::class);
    }
}
