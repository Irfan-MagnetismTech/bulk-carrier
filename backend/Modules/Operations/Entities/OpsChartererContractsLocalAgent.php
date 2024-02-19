<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsChartererContractsLocalAgent extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_charterer_contract_id',
        'port_code',
        'agent_name',
        'agent_billing_name',
        'agent_billing_email',
        'ops_charterer_contract_id',
    ];

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
    
    public function opsPort()
    {
        return $this->belongsTo(OpsPort::class, 'port_code' , 'code');
    }
}
