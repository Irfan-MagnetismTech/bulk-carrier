<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\GlobalSearchTrait;

class CrwCrewDocument extends Model
{
    use HasFactory, GlobalSearchTrait;

	protected $fillable = ['crw_crew_profile_id', 'document_name', 'issuing_authority', 'validity_period', 'validity_period_in_month', 'business_unit'];

	public function crwCrewDocumentRenewals(){
		return $this->hasMany(CrwCrewDocumentRenewal::class);
	}

    public function crwCrewProfile()
    {
        return $this->belongsTo(CrwCrewProfile::class);
    }
}
