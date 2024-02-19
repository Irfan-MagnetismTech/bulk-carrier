<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsCashRequisition extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'date',
        'requisition_by',
        'serial',
        'unit',
        'amount_in_words',
        'description',
        'salary_unit',
        'pf_no',
        'mobile_no',
        'purpose',
        'preferred_adjustment_method',
        'approximate_adjustment_date',
        'status',
        'business_unit',
        'approved_date',
        'received_date',
        'received_amount',
        'amount',
        'amount_bdt',
        'amount_usd',
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
    
}
