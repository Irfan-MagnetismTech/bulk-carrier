<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\CreateBusinessUnit;

class OpsChartererProfile extends Model
{
    use HasFactory, CreateBusinessUnit;

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

    public function opsChartererBankAccounts()
    {
        return $this->hasMany(OpsChartererBankAccount::class, 'ops_charterer_profile_id', 'id');
    }
}
