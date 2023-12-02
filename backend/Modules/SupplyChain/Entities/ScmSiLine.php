<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMaterial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\SupplyChain\Entities\ScmSrLine;
use Modules\SupplyChain\Entities\ScmSirLine;
use Modules\SupplyChain\Entities\scmSi;

class ScmSiLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_sr_id', 'scm_material_id', 'unit', 'quantity', 'sr_composite_key', 'si_composite_key',
    ];

    public function scmMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmMaterial::class);
    }

    public function scmSi(): BelongsTo
    {
        return $this->belongsTo(ScmSi::class);
    }


    public function scmSrLine(): BelongsTo
    {
        return $this->belongsTo(ScmSrLine::class, 'sr_composite_key', 'sr_composite_key');
    }

    public function scmSirLines(): HasMany
    {
        return $this->hasMany(ScmSirLine::class, 'si_composite_key', 'si_composite_key');
    }
}
