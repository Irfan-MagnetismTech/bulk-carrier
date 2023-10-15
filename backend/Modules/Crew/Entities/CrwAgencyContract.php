<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwAgencyContract extends Model
{
    use HasFactory;

	protected $fillable = ['crw_agency_id', 'billing_cycle', 'billing_currency', 'validity_from', 'validity_till', 'service_offered', 'terms_and_conditions', 'remarks', 'attachment', 'account_holder_name', 'bank_name', 'bank_address', 'account_no', 'swift_code', 'business_unit'];

    public function crwAgency()
    {
        return $this->belongsTo(CrwAgency::class);
    }    
}
