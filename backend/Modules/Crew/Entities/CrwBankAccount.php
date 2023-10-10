<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwBankAccount extends Model
{
    use HasFactory;

	protected $fillable = ['crw_crew_id', 'bank_name', 'account_holder', 'address', 'account_no', 'currency', 'swift_code', 'benificiary_name', 'benificiary_attachment', 'is_active', 'business_unit'];

    public function crwCrew()
    {
        return $this->belongsTo(CrwCrew::class);
    }  
}
