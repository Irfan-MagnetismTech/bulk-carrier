<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBulkNoonReportDistance extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_bulk_noon_report_id',
        'cp_ordered_speed',
        'reported_speed',
        'observed_distance',
        'engine_distance',
        'main_engine_revs',
        'slip_percent',
        'salinity',
        'ballast',
        'average_rpm',
        'fwd_draft',
        'mild_draft',
        'aft_draft',
        'heading',
        'steaming_hours',
        's_dwt',
        's_displacement',
        'status',
        'business_unit',
    ];
}
