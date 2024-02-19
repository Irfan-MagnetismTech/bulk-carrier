<?php

namespace Modules\Crew\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrwAgency extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    /**
     * @var array
     */
    protected $fillable = ['agency_name', 'legal_name', 'tax_identification', 'business_license_no', 'company_reg_no', 'address', 'phone', 'email', 'website', 'logo', 'country', 'business_unit'];

    /**
     * @var array
     */
    protected $skipForDeletionCheck = ['crwAgencyContactPersons'];

    /**
     * @var array
     */
    protected $features = [
        'crwAgencyContract' => 'Agency Contracts',
        'crwAgencyBill'     => 'Agency Bills',
        'crwCrewProfiles'   => 'Crew Profiles',
    ];

    /* ------------------------- Associate Relationship Start ------------------------- */
    /**
     * @return mixed
     */
    public function crwAgencyContactPersons(): HasMany
    {
        return $this->hasMany(CrwAgencyContactPerson::class, 'crw_agency_id', 'id');
    }

    /* ------------------------- Associate Relationship End ------------------------- */

    /* -------------------------  Belongs Relationship Start ------------------------- */
    /* -------------------------  Belongs Relationship End ------------------------- */

    /* -------------------------  Has Many Relationship Start ------------------------- */
    /**
     * @return mixed
     */
    public function crwAgencyContract(): HasMany
    {
        return $this->hasMany(CrwAgencyContract::class, 'crw_agency_id', 'id');
    }

    /**
     * @return mixed
     */
    public function crwAgencyBill(): HasMany
    {
        return $this->hasMany(CrwAgencyBill::class, 'crw_agency_id', 'id');
    }

    /**
     * @return mixed
     */
    public function crwCrewProfiles(): HasMany
    {
        return $this->hasMany(CrwCrewProfile::class, 'agency_id', 'id');
    }

    /* -------------------------  Has Many Relationship End ------------------------- */

}
