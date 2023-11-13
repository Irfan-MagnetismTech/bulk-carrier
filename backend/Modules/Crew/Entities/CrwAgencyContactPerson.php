<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CrwAgencyContactPerson extends Model
{
    use HasFactory;

    protected $table = 'crw_agency_contact_persons'; 

	protected $fillable = ['name', 'contact_no', 'email', 'position', 'purpose'];
}
