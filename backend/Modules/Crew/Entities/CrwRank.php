<?php

namespace Modules\Crew\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Crew\Entities\CrwVesselRequiredCrewLine;

class CrwRank extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $skipForDeletionCheck = ['scmPoLines', 'scmPoTerms', 'scmPoItems'];

    protected $features = [
        'scmLcRecords' => 'LC Records',
        'scmMrrs' => 'Material Receipt Reports',
    ];

    /**
     * @var array
     */
    protected $fillable = ['name', 'short_name', 'business_unit'];

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
