<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmCsPaymentInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_cs_id',
        'scm_cs_vendor_id',
        'scm_vendor_id',
        'payment_type',
        'payment_status',
        'name_of_bank',
        'cfr_value',
        'lc_margin',
        'bank_commission',
        'vat',
        'others',
        'insurence_premium',
        'document_value',
        'exchange_rate',
        'insurence_company',
    ];
}
