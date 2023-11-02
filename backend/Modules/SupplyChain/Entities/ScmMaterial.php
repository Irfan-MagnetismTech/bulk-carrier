<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmStockLedger;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\SupplyChain\Entities\ScmMaterialCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmMaterial extends Model
{
    use HasFactory;

    protected $appends = ['material_name_and_code'];

    protected $fillable = [
        'scm_material_category_id', 'name', 'material_code', 'hs_code', 'store_category', 'unit', 'minimum_stock', 'description', 'sample_photo', 'account_id'
    ];

    public function scmMaterialCategory(): BelongsTo
    {
        return $this->belongsTo(ScmMaterialCategory::class);
    }

    public function getMaterialNameAndCodeAttribute(): string
    {
        return $this->name . ' - ' . $this->material_code;
    }

    public function currentStockByMaterialAndWarehouse(): HasMany
    {
        return $this->hasMany(ScmStockLedger::class);
    }
}
