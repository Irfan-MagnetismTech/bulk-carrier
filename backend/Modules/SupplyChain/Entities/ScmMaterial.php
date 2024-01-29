<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Accounts\Entities\AccAccount;
use Modules\SupplyChain\Entities\ScmStockLedger;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\SupplyChain\Entities\ScmMaterialCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmMaterial extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $appends = ['material_name_and_code'];

    protected $fillable = [
        'scm_material_category_id', 'name', 'material_code', 'hs_code', 'store_category', 'unit', 'minimum_stock', 'description', 'sample_photo', 'account_id', 'type'
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

    public function account(): MorphOne
    {
        return $this->morphOne(AccAccount::class, 'accountable')->withDefault();
    }
}
