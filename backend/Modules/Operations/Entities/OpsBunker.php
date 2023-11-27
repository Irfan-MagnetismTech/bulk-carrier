<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Modules\SupplyChain\Entities\ScmVendor;
use Modules\SupplyChain\Entities\ScmMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBunker extends Model
{
    use HasFactory;

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
        'rate',
        'amount',
        'opening_balance',
        'amount_usd',
        'amount_bdt',
        'fuel_con_24h',
        'fuel_con_voyage',
        'currency',
        'status',
    ];

    // public function scmVendor()
    // {
    //     return $this->belongsTo(ScmVendor::class, 'scm_vendor_id' , 'id');
    // }

    public function scmMaterial()
    {
        return $this->belongsTo(ScmMaterial::class, 'scm_material_id' , 'id');
    }
    
    public function bunkerable()
    {
        return $this->morphTo();
    }

    public function opsBulkNoonReportConsumptions()
    {
        return $this->hasMany(OpsBulkNoonReportConsumption::class, 'ops_cargo_tariff_id', 'id');
    }
}
