<?php

namespace Modules\Crew\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrwAgency extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

	protected $fillable = ['agency_name', 'legal_name', 'tax_identification', 'business_license_no', 'company_reg_no', 'address', 'phone', 'email', 'website', 'logo', 'country', 'business_unit'];

	public function crwAgencyContactPersons(){
		return $this->hasMany(CrwAgencyContactPerson::class, 'crw_agency_id', 'id');
	}


    public function crwAgencyContract(): HasMany
    {
        return $this->hasMany(CrwAgencyContract::class, 'crw_agency_id', 'id');
    }

    public function crwAgencyBill(): HasMany
    {
        return $this->hasMany(CrwAgencyBill::class, 'crw_agency_id', 'id');
    }

    public function crwCrewProfiles(): HasMany
    {
        return $this->hasMany(CrwCrewProfile::class, 'agency_id', 'id');
    }	
}
