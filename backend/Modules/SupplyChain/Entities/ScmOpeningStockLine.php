<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmOpeningStockLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_opening_stock_id', 'scm_material_id', 'unit', 'rate', 'quantity',
    ];

    public function scmMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmMaterial::class)->withDefault();
    }    
}
