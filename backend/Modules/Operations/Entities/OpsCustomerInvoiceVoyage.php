<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsCustomerInvoiceVoyage extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_customer_invoice_id',
        'ops_voyage_id',
        'ops_vessel_id',  
        'ops_cargo_type_id',  
        'total_amount_bdt',
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
    

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }
    
    public function opsCargoType()
    {
        return $this->belongsTo(OpsCargoType::class, 'ops_cargo_type_id' , 'id');
    }

    public function opsCustomerInvoice()
    {
        return $this->belongsTo(OpsCustomerInvoice::class, 'ops_customer_invoice_id' , 'id');
    }
}
