<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBulkNoonReportPort extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_bulk_noon_report_id',
        'last_port',
        'next_port',
        'eta',
        'distance_run',
        'dtg',
        'remarks',
    ];
}
