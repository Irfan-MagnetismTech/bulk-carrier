<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMaterialCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_material_category_id', 'name', 'material_code', 'hs_code', 'store_category', 'unit', 'minimum_stock', 'description', 'sample_photo', 'account_id'
    ];

    public function scmMaterialCategory()
    {
        return $this->belongsTo(ScmMaterialCategory::class);
    }
}
