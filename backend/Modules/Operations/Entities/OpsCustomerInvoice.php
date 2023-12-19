<?php

namespace Modules\Operations\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operations\Entities\OpsCustomerInvoiceLine;

class OpsCustomerInvoice extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'ops_customer_id',
        'date',
        'sub_total_amount',
        'total_amount_bdt',
        'others_billable_amount',
        'service_fee_deduction_amount',
        'discounted_amount',
        'grand_total',
        'business_unit',

    ];

    public function opsCustomer()
    {
        return $this->belongsTo(OpsCustomer::class, 'ops_customer_id' , 'id');
    }

    public function opsCustomerInvoiceVoyages()
    {
        return $this->hasMany(OpsCustomerInvoiceVoyage::class, 'ops_customer_invoice_id', 'id');
    }
       
    public function opsCustomerInvoiceOthers()
    {
        return $this->hasMany(OpsCustomerInvoiceLine::class, 'ops_customer_invoice_id', 'id')->where('charge_or_deduct','charge');
    }
    
    public function opsCustomerInvoiceServices()
    {
        return $this->hasMany(OpsCustomerInvoiceLine::class, 'ops_customer_invoice_id', 'id')->where('charge_or_deduct','deduct');
    }

}
