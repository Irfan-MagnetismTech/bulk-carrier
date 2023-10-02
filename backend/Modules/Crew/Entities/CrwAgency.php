<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwAgency extends Model
{
    use HasFactory;

	protected $fillable = ['name', 'legal_name', 'tax_identification', 'business_license_no', 'company_reg_no', 'address', 'website', 'phone', 'email', 'logo', 'country', 'business_unit'];

	public function crwAgencyContactPeople(){
		return $this->hasMany(CrwAgencyContactPerson::class);
	}
}
