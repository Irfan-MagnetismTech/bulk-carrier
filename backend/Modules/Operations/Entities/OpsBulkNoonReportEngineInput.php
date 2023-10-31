<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBulkNoonReportEngineInput extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_bulk_noon_report_id',
        'type',
        'engine_unit',
        'pco',
        'rack',
        'exh_temp',
        'business_unit'

    ];
}
