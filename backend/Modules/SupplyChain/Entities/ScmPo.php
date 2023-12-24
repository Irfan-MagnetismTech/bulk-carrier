<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Entities\ScmMrr;
use Modules\SupplyChain\Entities\ScmPoLine;
use Modules\SupplyChain\Entities\ScmPoTerm;
use Modules\SupplyChain\Entities\ScmVendor;
use Modules\SupplyChain\Entities\ScmLcRecord;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Modules\SupplyChain\Traits\DeletedableModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmPo extends Model
{
    use HasFactory, GlobalSearchTrait, DeletedableModel;

    protected $fillable = [
        'ref_no', 'scm_pr_id', 'scm_cs_id', 'date', 'scm_vendor_id', 'scm_warehouse_id', 'acc_cost_center_id', 'currency', 'foreign_to_bdt', 'discount', 'vat', 'business_unit', 'created_by', 'sub_total', 'total_amount', 'net_amount', 'foreign_to_usd', 'pr_date', 'purchase_center', 'remarks',
    ];

    public function scmPoLines(): HasMany
    {
        return $this->hasMany(ScmPoLine::class);
    }

    public function scmPoTerms(): HasMany
    {
        return $this->hasMany(ScmPoTerm::class);
    }

    public function scmVendor(): BelongsTo
    {
        return $this->belongsTo(ScmVendor::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function scmPr(): BelongsTo
    {
        return $this->belongsTo(ScmPr::class);
    }

    public function scmCs(): BelongsTo
    {
        return $this->belongsTo(ScmCs::class);
    }

    public function scmLcRecords(): HasMany
    {
        return $this->hasMany(ScmLcRecord::class);
    }

    public function scmMrrs(): HasMany
    {
        return $this->hasMany(ScmMrr::class);
    }
}
