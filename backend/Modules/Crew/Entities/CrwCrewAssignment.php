<?php

namespace Modules\Crew\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Operations\Entities\OpsPort;
use Modules\Operations\Entities\OpsVessel;

class CrwCrewAssignment extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    /**
     * @var array
     */
    protected $fillable = ['ops_vessel_id', 'assignment_code', 'crw_crew_id', 'position_onboard', 'is_watchkeeper', 'joining_date', 'joining_port_code', 'duration', 'status', 'completion_date', 'completion_remarks', 'remarks', 'business_unit'];

    /**
     * @var array
     */
    protected $skipForDeletionCheck = ['opsVessel', 'crwCrewProfile', 'opsPort'];

    /**
     * @var array
     */
    protected $features = [
        'crwAttendanceLines'    => 'Crew Attendance',
        'appraisalRecord'       => 'Appraisal Records',
        'crwRestHourEntryLines' => 'Rest Hour Records',
    ];

    /* ------------------------- Associate Relationship Start ------------------------- */
    /* ------------------------- Associate Relationship End ------------------------- */

    /* -------------------------  Belongs Relationship Start ------------------------- */
    /**
     * @return mixed
     */
    public function opsVessel(): BelongsTo
    {
        return $this->belongsTo(OpsVessel::class);
    }

    /**
     * @return mixed
     */
    public function crwCrewProfile(): BelongsTo
    {
        return $this->belongsTo(CrwCrewProfile::class, 'crw_crew_id', 'id');
    }

    /**
     * @return mixed
     */
    public function opsPort(): BelongsTo
    {
        return $this->belongsTo(OpsPort::class, 'code', 'joining_port_code');
    }

    /* -------------------------  Belongs Relationship End ------------------------- */

    /* -------------------------  Has Many Relationship Start ------------------------- */
    /**
     * @return mixed
     */
    public function crwAttendanceLines(): HasMany
    {
        return $this->hasMany(CrwAttendanceLine::class, 'crw_crew_id', 'id');
    }

    /**
     * @return mixed
     */
    public function appraisalRecord(): HasOne
    {
        return $this->hasOne(AppraisalRecord::class, 'crw_crew_assignment_id', 'id');
    }

    /**
     * @return mixed
     */
    public function crwRestHourEntryLines(): HasMany
    {
        return $this->hasMany(CrwRestHourEntryLine::class, 'crw_crew_id', 'id');
    }

    /* -------------------------  Has Many Relationship End ------------------------- */

}
