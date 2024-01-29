<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmCs;
use Modules\SupplyChain\Entities\ScmPo;
use Modules\SupplyChain\Entities\ScmMrr;
use Modules\SupplyChain\Entities\ScmPrLine;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Modules\SupplyChain\Entities\ScmCsMaterial;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\SupplyChain\Entities\ScmCsMaterialVendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmPr extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'ref_no', 'scm_warehouse_id', 'acc_cost_center_id', 'attachment', 'raised_date', 'remarks', 'purchase_center', 'is_critical', 'approved_date', 'business_unit', 'created_by', 'closed_at', 'closed_by', 'is_closed', 'closing_remarks', 'status'
    ];

    public function scmPrLines(): HasMany
    {
        return $this->hasMany(ScmPrLine::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function scmPos(): HasMany
    {
        return $this->hasMany(ScmPo::class);
    }

    public function scmMrrs(): HasMany
    {
        return $this->hasMany(ScmMrr::class);
    }

    public function scmCss(): HasMany
    {
        return $this->hasMany(ScmCs::class);
    }


    public function scmCsMaterial(): HasMany
    {
        return $this->hasMany(ScmCsMaterial::class);
    }

    public function scmCsMaterialVendor(): HasMany
    {
        return $this->hasMany(ScmCsMaterialVendor::class);
    }

    // public function scmPo(): HasMany
    // {
    //     return $this->hasMany(ScmPo::class);
    // }

    // public function scmMrr(): HasMany
    // {
    //     return $this->hasMany(ScmMrr::class);
    // }

    // public function scmCs(): HasMany
    // {
    //     return $this->hasMany(ScmCs::class);
    // }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
