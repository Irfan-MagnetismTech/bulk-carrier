<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\GlobalSearchTrait;

class CrwCrewChecklist extends Model
{
    use HasFactory, GlobalSearchTrait;

	protected $fillable = ['effective_date', 'remarks', 'business_unit'];

	public function crwCrewChecklistLines(){
		return $this->hasMany(CrwCrewChecklistLine::class);
	}
}
