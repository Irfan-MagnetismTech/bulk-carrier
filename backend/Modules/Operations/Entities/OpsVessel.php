<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Operations\Entities\OpsPort;
use Modules\Operations\Entities\OpsVoyage;
use Modules\SupplyChain\Traits\StockLedger;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Modules\Operations\Entities\OpsHandoverTakeover;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Maintenance\Entities\MntCriticalVesselItem;

class OpsVessel extends Model
{
    use HasFactory, GlobalSearchTrait, StockLedger, DeletableModel;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    //  protected $skipForDeletionCheck = ['opsBunkers','opsVesselCertificates'];

    //  protected $features = [
    //      'mntCriticalVesselItems' => 'Critical Vessel Records',
    //      'opsCargoTariffs' => 'Cargo Tariff Records', 
    //      'scmWareHouse' => 'Warehouse Records',
    //  ];



    protected $fillable = [
        'vessel_type',
        'name',
        'short_code',
        'call_sign',
        'owner_name',
        'manager',
        'classification',
        'flag',
        'port_of_registry',
        'delivery_date',
        'grt',
        'nrt',
        'dwt',
        'imo',
        'official_number',
        'keel_laying_date',
        'launching_date',
        'mmsi',
        'overall_length',
        'overall_width',
        'year_built',
        'capacity',
        'total_cargo_hold',
        'live_tracking_config',
        'remarks',
        'dry_docking_months',
        'status',
        'business_unit'
    ];




    /**
     * The accessors to append to the model's array form.
     *
     * @var string[]
     */
    protected $appends = ['code_name'];

    /**
     * Concatenate the short code and name of the port.
     *
     * @return string
     */
    public function getCodeNameAttribute()
    {
        return $this->short_code . ' - ' . $this->name;
    }

    public function opsVesselCertificates()
    {
        
        return $this->hasMany(OpsVesselCertificate::class, 'ops_vessel_id', 'id');
    }

    public function opsBunkers()
    {
        return $this->morphMany(OpsBunker::class, 'bunkerable');
    }

    public function portOfRegistry()
    {
        return $this->belongsTo(OpsPort::class, 'port_of_registry', 'code');
    }

    public function mntCriticalVesselItems ()
    {
        return $this->hasMany(MntCriticalVesselItem::class)->where("is_critical", 1);
    }

    public function opsCargoTariffs()
    {
        return $this->hasMany(OpsCargoTariff::class, 'ops_vessel_id', 'id');
    }

    public function scmWareHouse()
    {
        return $this->hasOne(ScmWarehouse::class, 'ops_vessel_id' , 'id');
    }
    
    public function opsVesselBunkers() {
        return $this->hasMany(OpsVesselBunker::class, 'ops_vessel_id','id');
    }

    public function opsVoyages()
    {
        return $this->hasMany(OpsVoyage::class, 'ops_vessel_id', 'id');
    }

    public function opsHandoverTakeovers()
    {
        return $this->hasMany(OpsHandoverTakeover::class, 'ops_vessel_id', 'id');
    }

    public function scopeCurrentCharterer($query)
    {
        return $query->with($this->opsHandoverTakeovers);
    }
}
