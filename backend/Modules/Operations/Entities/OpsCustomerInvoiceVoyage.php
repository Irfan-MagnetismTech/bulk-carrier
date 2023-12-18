<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsCustomerInvoiceVoyage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_customer_invoice_id',
        'ops_contract_tariff_id',
        'ops_voyage_id',
        'ops_vessel_id',  
        'total_amount_bdt',

    ];
}
