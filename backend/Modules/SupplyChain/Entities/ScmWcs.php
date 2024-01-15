<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWcs extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_wr_id',
        'scm_warehouse_id',
        'acc_cost_center_id',
        'ref_no',
        'requirment_type',
        'special_instruction',
        'effective_date',
        'expire_date',
        'required_date',
        'remarks',
        'business_unit',
    ];
}
