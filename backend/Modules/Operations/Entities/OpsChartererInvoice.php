<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsChartererInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_charterer_profile_id',
        'ops_charterer_contract_id',
        'ops_voyage_id',
        'contract_type',
        'bill_from',
        'bill_till',
        'total_days',
        'total_amount',
        'others_billable_amount',
        'service_fee_deduction_amount',
        'discount_unit',
        'discounted_amount',
        'grand_total',
        'business_unit',

    ];

    public function opsChartererProfile()
    {
        return $this->belongsTo(OpsChartererProfile::class, 'ops_charterer_profile_id' , 'id');
    }
    
    public function opsChartererContract()
    {
        return $this->belongsTo(OpsChartererContract::class, 'ops_charterer_contract_id' , 'id');
    }

    public function opsChartererInvoiceLines()
    {
        return $this->hasMany(OpsChartererInvoiceLine::class, 'ops_charterer_invoice_id', 'id');
    }
}
