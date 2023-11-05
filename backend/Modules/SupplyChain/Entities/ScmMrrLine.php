<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmMrrLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_mrr_id', 'scm_material_id', 'unit', 'brand', 'model', 'quantity', 'rate', 'po_composite_key', 'pr_composite_key', 'net_rate',
    ];
}
