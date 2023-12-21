<?php

namespace Modules\Operations\Entities;

use App\Casts\DoubleToFloatCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBunkerBillLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_bunker_bill_id',
        'ops_bunker_bill_line_id',
        'particular',
        'requisition_material',
        'quantity',
        'rate',
        'exchange_rate_bdt',
        'exchange_rate_usd',
        'amount',
        'amount_bdt',
        'amount_usd',
    ];

    protected $casts = [
        'quantity' => DoubleToFloatCast::class,
        'rate' => DoubleToFloatCast::class,
        'exchange_rate_bdt' => DoubleToFloatCast::class,
        'exchange_rate_usd' => DoubleToFloatCast::class,
        'amount' => DoubleToFloatCast::class,
        'amount_bdt' => DoubleToFloatCast::class,
        'amount_usd' => DoubleToFloatCast::class,
    ];
}
