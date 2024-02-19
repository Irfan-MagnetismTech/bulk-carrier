<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsChartererBankAccount extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_charterer_profile_id',
        'bank_id',
        'bank_branch_id',
        'bank_name',
        'bank_branch_name',
        'account_name',
        'account_no',
        'swift_code',
        'routing_no',
        'currency',
        'country',
        'state_division',
        'city',
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
    
}
