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

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function opsContractTariff()
    {
        return $this->belongsTo(OpsContractTariff::class, 'ops_contract_tariff_id' , 'id');
    }

    public function opsCustomerInvoice()
    {
        return $this->belongsTo(OpsCustomerInvoice::class, 'ops_customer_invoice_id' , 'id');
    }
}
