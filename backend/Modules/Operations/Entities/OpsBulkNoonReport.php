<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Operations\Entities\OpsBunker;
use Modules\Operations\Entities\OpsVessel;
use Modules\Operations\Entities\OpsVoyage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Operations\Entities\OpsBulkNoonReportPort;
use Modules\Operations\Entities\OpsBulkNoonReportDistance;
use Modules\Operations\Entities\OpsBulkNoonReportCargoTank;
use Modules\Operations\Entities\OpsBulkNoonReportConsumption;
use Modules\Operations\Entities\OpsBulkNoonReportEngineInput;
use Modules\Operations\Entities\OpsBulkNoonReportConsumptionHead;

class OpsBulkNoonReport extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'ops_vessel_id',
        'ops_voyage_id',
        'ship_master',
        'chief_engineer',
        'wind_condition',
        'type',
        'date_time',
        'gmt_time',
        'location',
        'latitude',
        'longitude',
        'fuel_figures_from',
        'fw_last_day_noon_rob',
        'fw_production',
        'fw_consumption',
        'fw_today_noon_rob',
        'remarks',
        'status',
        'sea_condition',
        'business_unit',

    ];

    /**
    * @var array
    */
    protected $skipForDeletionCheck = ['relationName'];

    /**
    * @var array
    */
    protected $features = [
    // 'relationName'           => 'Menu',
    ];
    
    public function opsVessel():BelongsTo
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function opsVoyage():BelongsTo
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function opsBunkers():MorphMany
    {
        return $this->morphMany(OpsBunker::class, 'bunkerable');
    }

    public function opsBulkNoonReportPorts():HasMany
    {
        return $this->hasMany(OpsBulkNoonReportPort::class, 'ops_bulk_noon_report_id', 'id');
    }
    
    public function opsBulkNoonReportCargoTanks():HasMany
    {
        return $this->hasMany(OpsBulkNoonReportCargoTank::class, 'ops_bulk_noon_report_id', 'id');
    }

    public function opsBulkNoonReportConsumptions():HasMany
    {
        return $this->hasMany(OpsBulkNoonReportConsumption::class, 'ops_bulk_noon_report_id', 'id');
    }

    public function opsBulkNoonReportConsumptionHeads():HasMany
    {
        return $this->hasMany(OpsBulkNoonReportConsumptionHead::class, 'ops_bulk_noon_report_id', 'id');
    }

    public function opsBulkNoonReportDistance():HasOne
    {
        return $this->hasOne(OpsBulkNoonReportDistance::class, 'ops_bulk_noon_report_id', 'id');
    }

    public function opsBulkNoonReportEngineInputs():HasMany
    {
        return $this->hasMany(OpsBulkNoonReportEngineInput::class, 'ops_bulk_noon_report_id', 'id');
    }

    // public function opsBulkNoonReportEngineInputTypes():HasMany
    // {
    //     return $this->hasMany(OpsBulkNoonReportEngineInput::class, 'ops_bulk_noon_report_id', 'id')->whereNotNull('type');
    // }
}
