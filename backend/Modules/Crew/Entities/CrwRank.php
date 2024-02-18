<?php

namespace Modules\Crew\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Crew\Entities\CrwCrewProfile;
use Modules\Crew\Entities\CrwCrewRequisitionLine;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Crew\Entities\CrwVesselRequiredCrewLine;
use Modules\Crew\Entities\CrwRecruitmentApprovalLine;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CrwRank extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    /**
     * @var array
     */
    protected $fillable = ['name', 'short_name', 'business_unit'];

    /**
     * @var array
     */
    protected $features = [
        'crwVesselRequiredCrewLines'  => 'Vessel Crew Manning',
        'crwCrewRequisitionLines'     => 'Crew Requisitions',
        'crwRecruitmentApprovalLines' => 'Recruitment Approvals',
        'crwCrewProfiles'             => 'Crew Profiles',
    ];

    /**
     * @return mixed
     */
    public function crwVesselRequiredCrewLines(): HasMany
    {
        return $this->hasMany(CrwVesselRequiredCrewLine::class, 'crw_rank_id', 'id');
    }

    /**
     * @return mixed
     */
    public function crwCrewRequisitionLines(): HasMany
    {
        return $this->hasMany(CrwCrewRequisitionLine::class, 'crw_rank_id', 'id');
    }

    /**
     * @return mixed
     */
    public function crwRecruitmentApprovalLines(): HasMany
    {
        return $this->hasMany(CrwRecruitmentApprovalLine::class, 'crw_rank_id', 'id');
    }

    /**
     * @return mixed
     */
    public function crwCrewProfiles(): HasMany
    {
        return $this->hasMany(CrwCrewProfile::class, 'crw_rank_id', 'id');
    }
}
