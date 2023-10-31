<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmPoLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_po_id', 'scm_material_id', 'unit', 'brand', 'model', 'required_date', 'quantity', 'rate', 'total_price', 'item_cost', 'po_composite_key', 'pr_composite_key',
    ];
}
