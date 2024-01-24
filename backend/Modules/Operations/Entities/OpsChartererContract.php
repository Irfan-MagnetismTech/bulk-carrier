<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsChartererContract extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'contract_type',
        'contract_name',
        'ops_vessel_id',
        'ops_charterer_profile_id',
        'country',
        'address',
        'billing_address',
        'email',
        'contact_no',
        'bank_id',
        'bank_branch_id',
        'bank_name',
        'bank_branch_name',
        'attachment',
        'bank_account_no',
        'bank_account_name',
        'swift_code',
        'routing_no',
        'currency',
        'status',
        'business_unit',
    ];

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function opsChartererContractsFinancialTerms()
    {
        return $this->hasOne(OpsChartererContractsFinancialTerm::class, 'ops_charterer_contract_id', 'id');
    }

    public function opsChartererContractsLocalAgents()
    {
        return $this->hasMany(OpsChartererContractsLocalAgent::class, 'ops_charterer_contract_id', 'id');
    }

    public function opsChartererProfile()
    {
        return $this->belongsTo(OpsChartererProfile::class, 'ops_charterer_profile_id' , 'id');
    }

    public function opsChartererInvoices()
    {
        return $this->hasMany(OpsChartererInvoice::class, 'ops_charterer_contract_id', 'id');
    }

    public function dayWiseInvoices()
    {
        return $this->hasMany(OpsChartererInvoice::class, 'ops_charterer_contract_id', 'id')
                    ->where('contract_type', 'Day Wise')
                    ->latest('bill_till');
    }
}
