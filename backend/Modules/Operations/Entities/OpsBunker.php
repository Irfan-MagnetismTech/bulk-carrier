<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
<<<<<<< HEAD
=======
// use Modules\SupplyChain\Entities\ScmVendor;
>>>>>>> 158e6f40d907675af293440a6a580592cfc56d2c
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
        'amount',
        'opening_balance',
        'amount_usd',
        'amount_bdt',
        'fuel_con_24h',
        'fuel_con_voyage',
        'currency',
        'status',
    ];

<<<<<<< HEAD
    // public function scmVendor()
    // {
    //     return $this->belongsTo(ScmVendor::class, 'scm_vendor_id' , 'id');
    // }
=======
    public function scmVendor()
    {
        return $this->belongsTo(ScmVendor::class, 'scm_vendor_id' , 'id');
    }

>>>>>>> 158e6f40d907675af293440a6a580592cfc56d2c
    public function scmMaterial()
    {
        return $this->belongsTo(ScmMaterial::class, 'scm_material_id' , 'id');
    }
    
    public function bunkerable()
    {
        return $this->morphTo();
    }
}
