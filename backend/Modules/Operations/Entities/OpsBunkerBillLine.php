<?php

namespace Modules\Operations\Entities;

use App\Casts\DoubleToFloatCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBunkerBillLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_bunker_bill_id',
        'ops_bunker_requisition_id',
        'rate',
        'currency',
        'exchange_rate_bdt',
        'exchange_rate_usd',
        'amount',
        'amount_bdt',
        'amount_usd',
        'description',
    ];

    protected $casts = [
        'rate' => DoubleToFloatCast::class,
        'exchange_rate_bdt' => DoubleToFloatCast::class,
        'exchange_rate_usd' => DoubleToFloatCast::class,
        'amount' => DoubleToFloatCast::class,
        'amount_bdt' => DoubleToFloatCast::class,
        'amount_usd' => DoubleToFloatCast::class,
    ];

//     public function getExchangeRateBdtAttribute($value) {
//         return (float) $value;
//   }

    public function opsBunkerBillLineItems()
    {
        return $this->hasMany(OpsBunkerBillLineItem::class, 'ops_bunker_bill_line_id' , 'id');
    }

    public function opsBunkerRequisition() {
        return $this->hasOne(OpsBunkerRequisition::class, 'id', 'ops_bunker_requisition_id');
    }
}
