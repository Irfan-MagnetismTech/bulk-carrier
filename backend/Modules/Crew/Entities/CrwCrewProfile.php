<?php

namespace Modules\Crew\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Crew\Entities\CrwCrewEducation;

class CrwCrewProfile extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    /**
     * @var array
     */
    protected $fillable = ['crw_recruitment_approval_id', 'hired_by', 'agency_id', 'department_name', 'crw_rank_id', 'employee_type', 'is_officer', 'first_name', 'last_name', 'full_name', 'father_name', 'mother_name', 'date_of_birth', 'gender', 'religion', 'marital_status', 'nationality', 'nid_no', 'passport_no', 'passport_issue_date', 'blood_group', 'height', 'weight', 'pre_address', 'pre_city', 'pre_mobile_no', 'pre_email', 'per_address', 'per_city', 'per_mobile_no', 'per_email', 'picture', 'attachment', 'business_unit'];

    /**
     * @var array
     */
    protected $with = ['crwCurrentRank'];

    /**
     * @var array
     */
    protected $skipForDeletionCheck = ['educations', 'trainings', 'experiences', 'languages', 'references', 'nominees', 'crwRank', 'crwRecruitmentApproval', 'crwAgency'];

    /**
     * @var array
     */
    protected $features = [
        // 'crwCurrentRank'           => 'Vessel Crew Manning',
        'crwCrewAssignments'       => 'Crew Assignment',
        'crwSalaryStructures'      => 'Crew Salary Structure',
        'crwBankAccounts'          => 'Crew Bank Accounts',
        'crwAttendanceLines'       => 'Crew Attendance',
        'crwPayrollBatchHeadLines' => 'Salary',
        'crwPayrollBatchLines'     => 'Salary',
        'crewDocuments'            => 'Crew Documents',
        'appraisalRecords'         => 'Appraisal Records',
        'crwRestHourEntryLines'    => 'Rest Hour Records',
        'crwIncidentParticipants'  => 'Incidents Records',
    ];

    /* ------------------------- Associate Relationship Start ------------------------- */
    public function educations() : HasMany
    {
        return $this->hasMany(CrwCrewEducation::class);
    }

    public function trainings() : HasMany
    {
        return $this->hasMany(CrwCrewTraining::class);
    }

    public function experiences() : HasMany
    {
        return $this->hasMany(CrwCrewExperience::class);
    }

    public function languages() : HasMany
    {
        return $this->hasMany(CrwCrewLanguage::class);
    }

    public function references() : HasMany
    {
        return $this->hasMany(CrwCrewReference::class);
    }

    public function nominees() : HasMany
    {
        return $this->hasMany(CrwCrewNominee::class);
    }
    /* -------------------------  Associate Relationship End ------------------------- */

    /* -------------------------  Belongs Relationship Start ------------------------- */
    public function crwRank() : BelongsTo
    {
        return $this->belongsTo(CrwRank::class);
    }

    public function crwRecruitmentApproval() : BelongsTo
    {
        return $this->belongsTo(CrwRecruitmentApproval::class);
    }

    public function crwAgency() : BelongsTo
    {
        return $this->belongsTo(CrwAgency::class, 'agency_id', 'id');
    }

    /* ------------------------- Belongs Relationship End ------------------------- */

    /* -------------------------  Has Many Relationship Start ------------------------- */
    public function crwCurrentRank() : BelongsTo
    {
        return $this->belongsTo(CrwRank::class, 'crw_rank_id', 'id');
    }

    public function crwCrewAssignments() : HasMany
    {
        return $this->hasMany(CrwCrewAssignment::class, 'crw_crew_id', 'id');
    }

    public function crwSalaryStructures() : HasMany
    {
        return $this->hasMany(CrwSalaryStructure::class, 'crw_crew_id', 'id');
    }

    public function crwBankAccounts() : HasMany
    {
        return $this->hasMany(CrwBankAccount::class, 'crw_crew_id', 'id');
    }

    public function crwAttendanceLines() : HasMany
    {
        return $this->hasMany(CrwAttendanceLine::class, 'crw_crew_id', 'id');
    }

    public function crwPayrollBatchHeadLines() : HasMany
    {
        return $this->hasMany(CrwPayrollBatchHeadLine::class, 'crw_crew_id', 'id');
    }

    public function crwPayrollBatchLines() : HasMany
    {
        return $this->hasMany(CrwPayrollBatchLine::class, 'crw_crew_id', 'id');
    }

    public function crewDocuments() : HasMany
    {
        return $this->hasMany(CrwCrewDocument::class, 'crw_crew_profile_id', 'id');
    }

    public function appraisalRecords() : HasMany
    {
        return $this->hasMany(AppraisalRecord::class, 'crw_crew_id', 'id');
    }

    public function crwRestHourEntryLines() : HasMany
    {
        return $this->hasMany(CrwRestHourEntryLine::class, 'crw_crew_id', 'id');
    }

    public function crwIncidentParticipants() : HasMany
    {
        return $this->hasMany(CrwIncidentParticipant::class, 'crw_crew_id', 'id');
    }
    /* -------------------------  Has Many Relationship Start ------------------------- */

}
