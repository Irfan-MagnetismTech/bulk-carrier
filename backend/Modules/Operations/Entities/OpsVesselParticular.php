<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsVesselParticular extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_vessel_id',
        'attachment',
        'class_no',
        'loa',
        'depth',
        'ecdis_type',
        'maker_ssas',
        'engine_type',
        'bhp',
        'email',
        'lbc'
    ];

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }
}
