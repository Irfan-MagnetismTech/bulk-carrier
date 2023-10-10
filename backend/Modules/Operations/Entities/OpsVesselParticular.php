<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreateBusinessUnit;


class OpsVesselParticular extends Model
{
    use HasFactory, CreateBusinessUnit;
    // public static $snakeAttributes = false;

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
        'lbc',
        'business_unit'
    ];

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }
}
