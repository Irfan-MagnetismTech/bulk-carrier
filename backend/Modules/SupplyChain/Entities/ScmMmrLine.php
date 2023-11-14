<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMmr;
use Modules\SupplyChain\Entities\ScmMoLine;
use Modules\SupplyChain\Entities\ScmMaterial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmMmrLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_mmr_id', 'scm_material_id', 'specification', 'unit', 'quantity', 'mmr_composite_key',
    ];

    public function scmMmr(): BelongsTo
    {
        return $this->belongsTo(ScmMmr::class);
    }

    public function scmMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmMaterial::class);
    }

    public function scmMoLines()
    {
        return $this->hasMany(ScmMoLine::class, 'mmr_composite_key', 'mmr_composite_key');
    }
}
