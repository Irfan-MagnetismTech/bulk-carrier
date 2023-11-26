<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBulkNoonReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_vessel_id',
        'ops_voyage_id',
        'ship_master',
        'chief_engineer',
        'wind_condition',
        'type',
        'date_time',
        'gtm_time',
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


    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function opsBunkers()
    {
        return $this->morphMany(OpsBunker::class, 'bunkerable');
    }

    public function opsBulkNoonReportPorts()
    {
        return $this->hasMany(OpsBulkNoonReportPort::class, 'ops_bulk_noon_report_id', 'id');
    }
    public function opsBulkNoonReportCargoTanks()
    {
        return $this->hasMany(OpsBulkNoonReportCargoTank::class, 'ops_bulk_noon_report_id', 'id');
    }

    public function opsBulkNoonReportConsumptions()
    {
        return $this->hasMany(OpsBulkNoonReportConsumption::class, 'ops_bulk_noon_report_id', 'id');
    }

    public function opsBulkNoonReportDistance()
    {
        return $this->hasOne(OpsBulkNoonReportDistance::class, 'ops_bulk_noon_report_id', 'id');
    }

    public function opsBulkNoonReportEngineInputs()
    {
        return $this->hasMany(OpsBulkNoonReportEngineInput::class, 'ops_bulk_noon_report_id', 'id')->whereNull('type');
    }

    public function opsBulkNoonReportEngineInputTypes()
    {
        return $this->hasMany(OpsBulkNoonReportEngineInput::class, 'ops_bulk_noon_report_id', 'id')->whereNotNull('type');
    }
}
