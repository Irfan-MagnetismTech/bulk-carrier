<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmCsLandedCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_cs_id',
        'scm_cs_vendor_id',
        'scm_vendor_id',
        'hs_codes',
        'price_per_piece',
        'exchange_rate',
        'product_price',
        'freight_charge',
        'cfr_value',
        'insurance',
        'assesable_value_b',
        'landing_charge',
        'assesable_value_a',
        'cd',
        'rd',
        'sd',
        'vat',
        'ait',
        'at',
        'total_duty',
        'others',
        'total_landed_cost',
    ];
}
