<?php

namespace Modules\Operations\Entities;

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

    public function opsBunkerBillLineItems()
    {
        return $this->hasMany(OpsBunkerBillLineItem::class, 'ops_bunker_bill_line_id' , 'id');
    }

    public function opsBunkerRequisition() {
        return $this->hasOne(OpsBunkerRequisition::class, 'id', 'ops_bunker_requisition_id');
    }
}
