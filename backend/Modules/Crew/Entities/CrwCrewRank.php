<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GlobalSearchTrait;

class CrwCrewRank extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['crw_crew_id', 'crw_rank_id', 'rank_name', 'effective_date'];

    public function crwRank()
    {
        return $this->belongsTo(CrwRank::class);
    } 
}
