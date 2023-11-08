<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMaterial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmSiLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_sr_id', 'scm_material_id', 'unit', 'quantity', 'sr_composite_key',
    ];

    public function scmMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmMaterial::class);
    }

    public function scmSrLine(): BelongsTo
    {
        return $this->belongsTo(ScmSrLine::class, 'sr_composite_key', 'sr_composite_key');
    }
}
