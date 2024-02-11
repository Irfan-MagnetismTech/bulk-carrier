<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\UniqueKeyGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\SupplyChain\Entities\ScmVendorBillLine;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmVendorBill extends Model
{
    use HasFactory, UniqueKeyGenerator;

    protected $refKeyPrefix = 'VB';
    
    protected $fillable = [
        'scm_vendor_id',
        'ref_no',
        'date',
        'remarks',
        'attachment',
        'sub_total',
        'carring_cost',
        'loading_unloading',
        'clearence_charge',
        'port_charge',
        'grand_total',
        'discount',
        'net_amount',
        'business_unit',
        'created_by',
        'is_paid',
        'currency',
        'usd_to_bdt',
        'currency_to_usd',
    ];

    public function scmVendorBillLines(): HasMany
    {
        return $this->hasMany(ScmVendorBillLine::class);
    }
}
