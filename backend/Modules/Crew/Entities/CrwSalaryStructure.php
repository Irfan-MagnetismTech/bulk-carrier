<?php

namespace Modules\Crew\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrwSalaryStructure extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = ['crw_crew_id', 'promotion_id', 'increment_sequence', 'effective_date', 'currency', 'gross_salary', 'addition', 'deduction', 'net_amount', 'is_active', 'business_unit', 'remarks'];

    /**
     * @var array
     */
    protected $skipForDeletionCheck = ['crwSalaryStructureBreakdowns', 'crwCrewProfile'];

    /**
     * @var array
     */
    protected $features = [
        'crwAttendanceLines'   => 'Crew Attendance',
        'crwPayrollBatchLines' => 'Salary',
    ];

    /* ------------------------- Associate Relationship Start ------------------------- */
    public function crwSalaryStructureBreakdowns() : HasMany
    {
        return $this->hasMany(CrwSalaryStructureBreakdown::class);
    }

    /* ------------------------- Associate Relationship End ------------------------- */

    /* -------------------------  Belongs Relationship Start ------------------------- */
    public function crwCrewProfile() : BelongsTo
    {
        return $this->belongsTo(CrwCrewProfile::class, 'crw_crew_id', 'id');
    }

    /* -------------------------  Belongs Relationship End ------------------------- */

    /* -------------------------  Has Many Relationship Start ------------------------- */
    public function crwAttendanceLines(): HasMany
    {
        return $this->hasMany(CrwAttendanceLine::class, 'crw_crew_id', 'crw_crew_id');
    }

    public function crwPayrollBatchLines(): HasMany
    {
        return $this->hasMany(CrwPayrollBatchLine::class, 'crw_salary_structure_id', 'id');
    }

    /* -------------------------  Has Many Relationship End ------------------------- */
}
