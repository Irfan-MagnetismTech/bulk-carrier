<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\SupplyChain\Entities\ScmMaterial;

class ScmPoLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_po_id', 'scm_material_id', 'unit', 'brand', 'model', 'required_date', 'quantity', 'rate', 'total_price', 'net_rate', 'po_composite_key', 'pr_composite_key',
    ];

    public function scmMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmMaterial::class);
    }

}
