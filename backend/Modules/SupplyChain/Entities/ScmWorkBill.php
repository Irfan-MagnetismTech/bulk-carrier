<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWorkBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'scm_wo_id',
        // 'scm_wr_id',
        'scm_warehouse_id',
        'scm_vendor_id',
        'currency',
        'conversion_rate',
        'bill_amount',
        'security_amount',
        'adjustment_amount',
        'net_amount',
        'purchase_center',
        'status',
        'created_by',
        'attachment',
        'remarks',
        'business_unit',
    ];
}