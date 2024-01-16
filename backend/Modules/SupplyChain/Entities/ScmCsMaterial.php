<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMaterial;
use Modules\SupplyChain\Entities\ScmPr;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmCsMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_cs_id',
        'scm_pr_id',
        'scm_material_id',
        'cs_composite_key',
        'pr_composite_key',
        'quantity',
        'unit'
    ];

    public function scmCs(): BelongsTo
    {
        return $this->belongsTo(ScmCs::class);
    }

    public function scmMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmMaterial::class);
    }

    public function scmPr(): BelongsTo
    {
        return $this->belongsTo(ScmPr::class);
    }

    public function scmPrLine(): BelongsTo
    {
        return $this->belongsTo(ScmPrLine::class, 'pr_composite_key', 'pr_composite_key');
    }
}
