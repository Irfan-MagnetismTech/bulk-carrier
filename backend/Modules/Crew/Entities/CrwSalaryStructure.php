<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\GlobalSearchTrait;

class CrwSalaryStructure extends Model
{
    use HasFactory, GlobalSearchTrait;

	protected $fillable = ['crw_crew_id', 'increment_sequence', 'effective_date', 'promotion_id', 'currency', 'gross_salary', 'is_active', 'business_unit'];

	public function crwSalaryStructureBreakdowns(){
		return $this->hasMany(CrwSalaryStructureBreakdown::class);
	}

	public function crwCrew()
    {
        return $this->belongsTo(CrwCrew::class);
    }  
}
