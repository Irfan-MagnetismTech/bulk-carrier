<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMmrLine;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScmMoLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_mo_id', 'scm_material_id', 'unit', 'quantity', 'remarks', 'mmr_composite_key','mo_composite_key'
    ];

    public function scmMmrLine(): BelongsTo
    {
        return $this->belongsTo(ScmMmrLine::class, 'mmr_composite_key', 'mmr_composite_key');
    }

    public function scmMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmMaterial::class);
    }

    public function scmMiLines(): HasMany
    {
        return $this->hasMany(ScmMiLine::class, 'mo_composite_key', 'mo_composite_key');
    }
}
