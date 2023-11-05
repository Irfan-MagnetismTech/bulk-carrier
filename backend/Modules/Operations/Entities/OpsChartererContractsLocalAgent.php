<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsChartererContractsLocalAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_charterer_contract_id',
        'port_code',
        'agent_name',
        'agent_billing_name',
        'agent_billing_email',
        'ops_charterer_contract_id',
    ];
}
