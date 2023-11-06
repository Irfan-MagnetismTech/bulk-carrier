<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMrr;
use Modules\SupplyChain\Entities\ScmMaterial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmMrrLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_mrr_id', 'scm_material_id', 'unit', 'brand', 'model', 'quantity', 'rate', 'po_composite_key', 'pr_composite_key', 'net_rate',
    ];

    public function scmMrr(): BelongsTo
    {
        return $this->belongsTo(ScmMrr::class);
    }

    public function scmMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmMaterial::class);
    }    
}
