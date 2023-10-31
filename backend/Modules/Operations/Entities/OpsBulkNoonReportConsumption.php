<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBulkNoonReportConsumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_bulk_noon_report_id',
        'type',
        'previous_rob',
        'received',
        'me',
        'ge',
        'blr',
        'ig',
        'aux',
        'main',
        'me_cyl_oil',
        'ge_sys_oil',
        'rob',
    ];
}
