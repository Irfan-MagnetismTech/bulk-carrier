<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreateBusinessUnit;

class OpsChartererInvoiceLine extends Model
{
    use HasFactory, CreateBusinessUnit;

    protected $fillable = [
        'ops_charterer_invoice_id',
        'charge_or_deduct',
        'particular',
        'cost_unit',
        'currency',
        'quantity',
        'rate',
        'exchange_rate_bdt',
        'exchange_rate_usd',
        'amount',
        'amount_bdt',
        'amount_usd',
        'business_unit',
    ];
}
