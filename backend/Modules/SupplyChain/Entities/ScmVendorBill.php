<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use App\Traits\UniqueKeyGenerator;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmVendor;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\SupplyChain\Entities\ScmVendorBillLine;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function scmVendor(): BelongsTo
    {
        return $this->belongsTo(ScmVendor::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
