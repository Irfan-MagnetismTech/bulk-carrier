<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsChartererInvoice extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'ops_charterer_profile_id',
        'ops_charterer_contract_id',
        'ops_voyage_id',
        'contract_type',
        'bill_from',
        'bill_till',
        'total_days',
        'total_amount',
        'per_day_charge',
        'others_billable_amount',
        'service_fee_deduction_amount',
        'discount_unit',
        'discounted_amount',
        'grand_total',
        'business_unit',

    ];

    /**
    * @var array
    */
    protected $skipForDeletionCheck = [''];
    
    /**
    * @var array
    */
    protected $features = [
    // 'relationName'           => 'Menu',
    ];
    

    public function opsChartererProfile()
    {
        return $this->belongsTo(OpsChartererProfile::class, 'ops_charterer_profile_id' , 'id');
    }
    
    public function opsChartererContract()
    {
        return $this->belongsTo(OpsChartererContract::class, 'ops_charterer_contract_id' , 'id');
    }

    public function opsChartererInvoiceVoyages()
    {
        return $this->hasMany(OpsChartererInvoiceVoyage::class, 'ops_charterer_invoice_id', 'id');
    }
    
    public function opsChartererInvoiceOthers()
    {
        return $this->hasMany(OpsChartererInvoiceLine::class, 'ops_charterer_invoice_id', 'id')->where('charge_or_deduct','charge');
    }
    
    public function opsChartererInvoiceServices()
    {
        return $this->hasMany(OpsChartererInvoiceLine::class, 'ops_charterer_invoice_id', 'id')->where('charge_or_deduct','deduct');
    }

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }
}
