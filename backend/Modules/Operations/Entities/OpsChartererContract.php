<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsChartererContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_type',
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
    public function opsChartererProfile()
    {
        return $this->belongsTo(OpsChartererProfile::class, 'ops_charterer_profile_id' , 'id');
    }
}
