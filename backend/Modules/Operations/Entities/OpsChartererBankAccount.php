<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreateBusinessUnit;

class OpsChartererBankAccount extends Model
{
    use HasFactory, CreateBusinessUnit;

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
}
