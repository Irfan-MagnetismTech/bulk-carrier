<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsContractAssign extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'ops_vessel_id',
        'ops_voyage_id',
        'ops_cargo_tariff_id',
        'ops_customer_id',
        'ops_charterer_profile_id',
        'ops_charterer_contract_id',
        'assign_date',
        'remarks',
        'contract_assign_type',
        'business_unit',
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
    
    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function opsCargoTariff()
    {
        return $this->belongsTo(OpsCargoTariff::class, 'ops_cargo_tariff_id' , 'id');
    }

    public function opsCustomer()
    {
        return $this->belongsTo(OpsCustomer::class, 'ops_customer_id' , 'id');
    }
    
    public function opsChartererProfile()
    {
        return $this->belongsTo(OpsChartererProfile::class, 'ops_charterer_profile_id' , 'id');
    }
    
    public function opsChartererContract()
    {
        return $this->belongsTo(OpsChartererContract::class, 'ops_charterer_contract_id' , 'id');
    }

    public function opsContractTariffs() {
        return $this->hasMany(OpsContractTariff::class, 'ops_contract_assign_id', 'id');
    }
}


