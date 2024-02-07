<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmVendorBill;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmVendorBillLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_vendor_bill_id',
        'ref_no',
        'date'
    ];

    public function scmVendorBill(): BelongsTo
    {
        return $this->belongsTo(ScmVendorBill::class);
    }
}
