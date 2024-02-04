<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmMrrItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_mrr_line_id',
        'scm_material_id',
        'unit',
        'brand',
        'model',
        'tolerance_quantity',
        'quantity',
        'rate',
        'po_composite_key',
        'pr_composite_key',
        'net_rate',
    ];
}
