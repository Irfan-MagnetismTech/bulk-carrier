<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmPo;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Entities\ScmMrrLine;
use Modules\SupplyChain\Entities\ScmLcRecord;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Modules\SupplyChain\Entities\ScmStockLedger;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Accounts\Entities\AccCashRequisition;

class ScmMrr extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'ref_no', 'type', 'date', 'scm_po_id', 'scm_pr_id', 'scm_warehouse_id', 'scm_lc_record_id', 'scm_cs_id', 'acc_cost_center_id', 'remarks', 'challan_no', 'is_qc_passed', 'qc_remarks', 'business_unit', 'created_by', 'is_completed', 'acc_cash_requisition_id', 'purchase_center',
    ];

    public function scmMrrLines(): HasMany
    {
        return $this->hasMany(ScmMrrLine::class);
    }

    public function scmPo(): BelongsTo
    {
        return $this->belongsTo(ScmPo::class);
    }

    public function scmPr(): BelongsTo
    {
        return $this->belongsTo(ScmPr::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function scmLcRecord(): BelongsTo
    {
        return $this->belongsTo(ScmLcRecord::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function stockable()
    {
        return $this->morphMany(ScmStockLedger::class, 'stockable');
    }

    public function accCashRequisition(): BelongsTo
    {
        return $this->belongsTo(AccCashRequisition::class);
    }
}
