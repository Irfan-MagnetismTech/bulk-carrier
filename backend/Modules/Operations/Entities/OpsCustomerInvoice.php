<?php

namespace Modules\Operations\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsCustomerInvoice extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'ops_customer_id',
        'sub_total',
        'discount',
        'grand_total',
        'business_unit'
    ];

    public function opsCustomer()
    {
        return $this->belongsTo(OpsCustomer::class, 'ops_customer_id' , 'id');
    }

    public function opsCustomerInvoiceLines()
    {
        return $this->hasMany(OpsCustomerInvoiceLine::class, 'ops_customer_invoice_id', 'id');
    }


}
