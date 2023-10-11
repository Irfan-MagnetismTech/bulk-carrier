<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVoyagePortSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_voyage_id',
        'port_code',
        'eta',
        'etb',
        'etd',
        'load_commence',
        'load_complete'
        'unload_commence',
        'unload_complete',
        'operation_type',
        'business_unit'
    ];
}
