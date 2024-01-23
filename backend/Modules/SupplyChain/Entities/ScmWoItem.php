<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_wo_line_id',
        'scm_service_id',
        'description',
        'received_date',
        'quantity',
        'rate',
        'total',
        'wr_composite_key',
        'wo_composite_key',
        'wcs_composite_key',
    ];
}
