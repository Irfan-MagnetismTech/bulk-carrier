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
        'scm_mrr_id',
        'scm_vendor_bill_id',
        'scm_po_id',
        'scm_lc_record_id',
        'amount',
    ];

    public function scmVendorBill(): BelongsTo
    {
        return $this->belongsTo(ScmVendorBill::class);
    }
}
