<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Entities\ScmMaterial;
use Modules\SupplyChain\Entities\ScmStockLedger;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmPrLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_pr_id', 'scm_material_id', 'unit', 'brand', 'model', 'country_id', 'sample_file', 'drawing_no', 'part_no', 'specification', 'quantity', 'required_date', 'pr_composite_key',
    ];

    public function scmPr(): BelongsTo
    {
        return $this->belongsTo(ScmPr::class);
    }

    public function scmMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmMaterial::class);
    }

    public function scmStockLedgers(): HasMany
    {
        return $this->hasMany(ScmStockLedger::class, 'scm_material_id', 'scm_material_id');
    }

    public function scmPoLines(): HasMany
    {
        return $this->hasMany(ScmPoLine::class, 'pr_composite_key', 'pr_composite_key');
    }
}
