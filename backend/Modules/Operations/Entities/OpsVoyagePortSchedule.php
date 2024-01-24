<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
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
        'ata',
        'atb',
        'atd',
        'load_commence',
        'load_complete',
        'unload_commence',
        'unload_complete',
        'operation_type',
        'business_unit'
    ];
    
    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function portCode() {
        return $this->belongsTo(OpsPort::class, 'port_code', 'code');
    }
}
