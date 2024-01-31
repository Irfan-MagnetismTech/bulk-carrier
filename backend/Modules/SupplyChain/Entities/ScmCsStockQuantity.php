<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMaterial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmCsStockQuantity extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_cs_id',
        'scm_material_id',
        'at_port',
        'in_transit',
        'under_lc',
        'total_stock',
        'days_to_run',
        'available_in_other_unit',
    ];

    public function scmMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmMaterial::class);
    }
}
