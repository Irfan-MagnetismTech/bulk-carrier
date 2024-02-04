<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWrr extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_wo_id',
        'ref_no',
        'date',
        'receive_date',
        'scm_warehouse_id',
        'purchase_center',
        'remarks',
        'qc_remarks',
        'business_unit',
        'created_by'
    ];
}
