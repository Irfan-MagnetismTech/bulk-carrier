<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBunkerBillLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_bunker_bill_id',
        'pr_no',
        'rate',
        'exchange_rate_bdt',
        'exchange_rate_usd',
        'amount',
        'amount_bdt',
        'amount_usd',
        'description',
    ];
}
