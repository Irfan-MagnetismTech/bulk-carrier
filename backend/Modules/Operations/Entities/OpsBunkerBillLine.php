<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBunkerBillLine extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_bunker_bill_id',
        'ops_bunker_requisition_id',
        'rate',
        'currency',
        'exchange_rate_bdt',
        'exchange_rate_usd',
        'amount',
        'amount_bdt',
        'amount_usd',
        'description',
    ];

    protected $casts = [
        'rate' => 'float',
        'exchange_rate_bdt' => 'float',
        'exchange_rate_usd' => 'float',
        'amount' => 'float',
        'amount_bdt' => 'float',
        'amount_usd' => 'float',
    ];

    /**
    * @var array
    */
    protected $skipForDeletionCheck = ['relationName'];
    
    /**
    * @var array
    */
    protected $features = [
    // 'relationName'           => 'Menu',
    ];
    

    public function opsBunkerBillLineItems()
    {
        return $this->hasMany(OpsBunkerBillLineItem::class, 'ops_bunker_bill_line_id', 'id');
    }

    public function opsBunkerRequisition()
    {
        return $this->hasOne(OpsBunkerRequisition::class, 'id', 'ops_bunker_requisition_id');
    }
}
