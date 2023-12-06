<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmCsMaterialVendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_cs_id', 'scm_cs_vendor_id', 'scm_cs_material_id', 'scm_material_id', 'brand', 'origin', 'stock_type', 'manufacturing_days', 'offered_price', 'negotiated_price', 'quantity', 'amount',
    ];
}
