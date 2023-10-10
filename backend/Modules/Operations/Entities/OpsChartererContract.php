<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreateBusinessUnit;


class OpsChartererContract extends Model
{
    use HasFactory, CreateBusinessUnit;

    protected $fillable = [
        'contract_type',
        'ops_vessel_id',
        'ops_charterer_profile_id',
        'country',
        'address',
        'billing_address',
        'email',
        'billing_email',
        'contact_no',
        'bank_branche_id',
        'attachment',
        'bank_id',
        'bank_account_name',
        'bank_account_no',
        'swift_code',
        'routing_no',
        'currency',
        'status',
        'port_code',
        'agent_name',
        'billing_name',
        'business_unit',
    ];

    public function ops_vessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }
    public function ops_charterer_profile()
    {
        return $this->belongsTo(OpsChartererProfile::class, 'ops_charterer_profile_id' , 'id');
    }
}
