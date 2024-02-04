<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWrr extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_no',
        'date',
        'wo_no',
        'receive_date',
        'scm_warehouse_id',
        'purchase_center',
        'remarks',
        'qc_remarks',
        'business_unit',
        'created_by'
    ];
}
