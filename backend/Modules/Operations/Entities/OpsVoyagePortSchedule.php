<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\CreateBusinessUnit;

class OpsVoyagePortSchedule extends Model
{
    use HasFactory, CreateBusinessUnit;

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
