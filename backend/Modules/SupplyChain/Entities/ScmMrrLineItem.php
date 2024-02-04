<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScmMrrLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_mrr_line_id',
        'scm_material_id',
        'unit',
        'brand',
        'model',
        'tolerence_quantity',
        'quantity',
        'rate',
        'po_composite_key',
        'pr_composite_key',
        'net_rate',
    ];

    public function scmMrrLine(): BelongsTo
    {
        return $this->belongsTo(ScmMrrLine::class);
    }

    public function scmMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmMaterial::class);
    }

    public function scmPrLine(): BelongsTo
    {
        return $this->belongsTo(ScmPrLine::class, 'pr_composite_key', 'pr_composite_key');
    }

    public function scmPoLine(): BelongsTo
    {
        return $this->belongsTo(ScmPoLine::class, 'po_composite_key', 'po_composite_key');
    }
}
