<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwVesselRequiredCrewLine extends Model
{
    use HasFactory;

	protected $fillable = ['crw_rank_id', 'required_manpower', 'eligibility', 'remarks'];

    public function crwRank()
    {
        return $this->belongsTo(CrwRank::class);
    }
}
