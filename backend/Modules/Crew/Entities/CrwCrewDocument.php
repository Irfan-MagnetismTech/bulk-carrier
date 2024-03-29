<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrewDocument extends Model
{
    use HasFactory;

	protected $fillable = ['crw_crew_id', 'reference_no', 'name', 'issuing_authority', 'validity_period', 'business_unit'];

	public function crwCrewDocumentRenewals(){
		return $this->hasMany(CrwCrewDocumentRenewal::class);
	}

    public function crwCrew()
    {
        return $this->belongsTo(CrwCrew::class);
    }  	
}
