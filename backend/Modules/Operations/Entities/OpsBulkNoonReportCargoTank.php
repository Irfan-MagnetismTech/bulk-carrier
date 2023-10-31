<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBulkNoonReportCargoTank extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_bulk_noon_report_id',
        'cargo_tanks',
        'liq_level',
        'pressure',
        'vapor_temp',
        'liq_temp',
        'quantity_mt',
    ];
}
