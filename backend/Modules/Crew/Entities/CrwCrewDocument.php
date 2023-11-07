<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrewDocument extends Model
{
    use HasFactory;

	protected $fillable = ['crw_crew_id', 'name', 'issuing_authority', 'validity_period', 'validity_period_in_month', 'business_unit'];

	public function crwCrewDocumentRenewals(){
		return $this->hasMany(CrwCrewDocumentRenewal::class);
	}

    public function crwCrew()
    {
        return $this->belongsTo(CrwCrew::class);
    }
}
