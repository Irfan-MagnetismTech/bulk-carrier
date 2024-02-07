<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\GlobalSearchTrait;

class CrwBankAccount extends Model
{
    use HasFactory, GlobalSearchTrait;

	protected $fillable = ['crw_crew_id', 'bank_name', 'branch_name', 'routing_number', 'account_name', 'account_number', 'benificiary_name', 'attachment', 'is_active', 'business_unit'];

    public function crwCrewProfile()
    {
        return $this->belongsTo(CrwCrewProfile::class, 'crw_crew_id', 'id');
    }  
}
