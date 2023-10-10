<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwAgencyBill extends Model
{
    use HasFactory;

	protected $fillable = ['crw_agency_id', 'crw_agency_contract_id', 'applied_date', 'invoice_date', 'invoice_no', 'invoice_type', 'invoice_currency', 'invoice_amount', 'grand_total', 'discount', 'net_amount', 'remarks', 'business_unit'];

	public function crwAgencyBillLines(){
		return $this->hasMany(CrwAgencyBillLine::class);
	}

	public function crwAgency()
    {
        return $this->belongsTo(CrwAgency::class);
    } 
}
