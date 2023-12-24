<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsCustomerInvoiceLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_customer_invoice_id',
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

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function opsCustomerInvoice()
    {
        return $this->belongsTo(OpsCustomerInvoice::class, 'ops_customer_invoice_id' , 'id');
    }
}
