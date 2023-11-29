<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmAdjustment;
use Modules\SupplyChain\Entities\ScmMaterial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmAdjustmentLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_mmr_id', 'scm_material_id', 'specification', 'unit', 'quantity', 'mmr_composite_key',
    ];

    public function scmAdjustment(): BelongsTo
    {
        return $this->belongsTo(ScmAdjustment::class);
    }

    public function scmMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmMaterial::class);
    }
}