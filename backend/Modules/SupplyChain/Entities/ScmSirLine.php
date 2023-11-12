<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmSiLine;
use Modules\SupplyChain\Entities\ScmSrLine;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SupplyChain\Entities\ScmMaterial;

class ScmSirLine extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'scm_sir_id', 'scm_material_id', 'unit', 'quantity', 'notes', 'si_composite_key', 'sr_composite_key',
    ];

    public function scmMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmMaterial::class);
    }

    public function scmSrLine(): BelongsTo
    {
        return $this->belongsTo(ScmSrLine::class, 'sr_composite_key', 'sr_composite_key');
    }

    public function scmSiLine(): BelongsTo
    {
        return $this->belongsTo(ScmSiLine::class, 'si_composite_key', 'si_composite_key');
    }
}
