<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmMiShortageLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_mi_shortage_id',
        'scm_material_id',
        'unit',
        'quantity',
        'remarks',
    ];

    public function scmMiShortage()
    {
        return $this->belongsTo(ScmMiShortage::class);
    }

    public function scmMaterial()
    {
        return $this->belongsTo(ScmMaterial::class);
    }

    
}
