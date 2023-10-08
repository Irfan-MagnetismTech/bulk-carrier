<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsBunker extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bunkerable_type',
        'bunkerable_id',
        'scm_vendor_id',
        'scm_material_id',
        'unit',
        'quantity',
        'requested_quantity',
        'exchange_rate_bdt',
        'exchange_rate_usd',
        'amount',
        'opening_balance',
        'amount_usd',
        'amount_bdt',
        'fuel_con_24h',
        'fuel_con_voyage',
        'currency',
        'status',
    ];

    // public function scm_vendor()
    // {
    //     return $this->belongsTo(ScmVendor::class, 'scm_vendor_id' , 'id');
    // }
    // public function scm_material()
    // {
    //     return $this->belongsTo(ScmMaterial::class, 'scm_material_id' , 'id');
    // }
    
    public function bunkerable()
    {
        return $this->morphTo();
    }
}
