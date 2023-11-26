<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SupplyChain\Entities\ScmMmrLine;
use Modules\SupplyChain\Entities\ScmMoLine;
use Modules\SupplyChain\Entities\ScmMaterial;
use Modules\SupplyChain\Entities\ScmMi;

class ScmMiLine extends Model
{
    use HasFactory;

    protected $fillable = [
        "scm_material_id",
        "unit",
        "quantity",
        "remarks",
        "mmr_composite_key",
        "mi_composite_key",
        "mo_composite_key",
    ];

    public function scmMaterial()
    {
        return $this->belongsTo(ScmMaterial::class);
    }

    public function scmMi()
    {
        return $this->belongsTo(ScmMi::class);
    }

    public function scmMoLine()
    {
        return $this->belongsTo(ScmMoLine::class, 'mo_composite_key', 'mo_composite_key');
    }

    public function scmMmrLine()
    {
        return $this->belongsTo(ScmMmrLine::class, 'mmr_composite_key', 'mmr_composite_key');
    }
}
