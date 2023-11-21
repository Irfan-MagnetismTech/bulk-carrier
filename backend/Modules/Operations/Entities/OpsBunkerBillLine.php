<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBunkerBillLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_bunker_bill_id',
        'pr_no',
        'amount'
    ];
}
