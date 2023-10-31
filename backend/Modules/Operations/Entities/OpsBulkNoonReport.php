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
}
