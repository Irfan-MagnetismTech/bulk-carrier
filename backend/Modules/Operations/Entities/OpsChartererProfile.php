<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsChartererProfile extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

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

    protected $appends = ['name_and_code'];

    /**
    * @var array
    */
    protected $skipForDeletionCheck = [''];
    
    /**
    * @var array
    */
    protected $features = [
    // 'relationName'           => 'Menu',
    ];
    

    public function opsChartererBankAccounts()
    {
        return $this->hasMany(OpsChartererBankAccount::class, 'ops_charterer_profile_id', 'id');
    }

    
    public function getNameAndCodeAttribute(): string
    {
        return $this->name . ' - ' . $this->owner_code;
    }

    public function opsChartererInvoices()
    {
        return $this->hasMany(OpsChartererInvoice::class, 'ops_charterer_profile_id', 'id');
    }
}
