<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\GlobalSearchTrait;

class CrwSalaryStructure extends Model
{
    use HasFactory, GlobalSearchTrait;

	protected $fillable = ['crw_crew_id', 'promotion_id', 'increment_sequence', 'effective_date', 'currency', 'gross_salary', 'addition', 'deduction', 'net_amount', 'is_active', 'business_unit', 'remarks'];

	public function crwSalaryStructureBreakdowns(){
		return $this->hasMany(CrwSalaryStructureBreakdown::class);
	}

	public function crwCrew()
    {
        return $this->belongsTo(CrwCrewProfile::class, 'crw_crew_id', 'id');
    }
}
