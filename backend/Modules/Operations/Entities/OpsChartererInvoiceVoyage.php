<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsChartererInvoiceVoyage extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_charterer_invoice_id',
        'ops_voyage_id',
        'cargo_quantity',
        'initial_loading_point',
        'final_loading_point',
        'rate_per_mt',
        'total_amount',
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

}
