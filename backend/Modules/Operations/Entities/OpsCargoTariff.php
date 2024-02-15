<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Operations\Entities\OpsPort;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsCargoTariff extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'tariff_name',
        'ops_vessel_id',
        'loading_point',
        'unloading_point',
        'pol_pod',
        'ops_cargo_type_id',
        'currency',
        'status',
        'business_unit',
    ];

    protected $skipForDeletionCheck = ['opsCargoType', 'opsCargoTariffLines', 'opsVessel', 'loadingPoint','unloadingPoint'];

    protected $features = [
        'opsChartererContractsFinancialTerms' => 'Charterer Contracts Financial Terms Records',
        'opsContractAssigns' => 'Contract Assigns Records',
        'opsContractTariffs' => 'Contract Tariffs Records',
        'opsVoyages' => 'Voyages Records',
        'opsVoyageSectors' => 'Voyage Sectors Records',
    ];
    
    public function opsCargoType()
    {
        return $this->belongsTo(OpsCargoType::class, 'ops_cargo_type_id' , 'id');
    }
    
    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function opsCargoTariffLines()
    {
        return $this->hasMany(OpsCargoTariffLine::class, 'ops_cargo_tariff_id', 'id');
    }

    public function loadingPoint() {
        return $this->belongsTo(OpsPort::class, 'loading_point', 'code');
    }

    public function unloadingPoint() {
        return $this->belongsTo(OpsPort::class, 'unloading_point', 'code');
    }

    // for check only deleteable
    public function opsChartererContractsFinancialTerms()
    {
        return $this->hasMany(OpsChartererContractsFinancialTerm::class, 'ops_cargo_tariff_id', 'id');
    }

    public function opsContractAssigns()
    {
        return $this->hasMany(OpsContractAssign::class, 'ops_cargo_tariff_id', 'id');
    }

    public function opsContractTariffs()
    {
        return $this->hasMany(OpsContractTariff::class, 'ops_cargo_tariff_id', 'id');
    }

    public function opsVoyages()
    {
        return $this->hasMany(OpsVoyage::class, 'ops_cargo_tariff_id', 'id');
    }

    public function opsVoyageSectors()
    {
        return $this->hasMany(OpsVoyageSector::class, 'ops_cargo_tariff_id', 'id');
    }
}
