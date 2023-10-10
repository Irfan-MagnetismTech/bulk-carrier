<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsChartererProfile extends Model
{
    use HasFactory;
    use \App\Traits\CreateBusinessUnit;

    protected $fillable = [
        'company_legal_name',
        'name',
        'owner_code',
        'country',
        'contact_no',
        'address',
        'billing_address',
        'billing_email',
        'email',
        'website',
        'business_unit'
    ];

    public function ops_charterer_bank_accounts()
    {
        return $this->hasMany(OpsChartererBankAccount::class, 'ops_charterer_profile_id', 'id');
    }
}
