<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmPo;
use Modules\SupplyChain\Entities\ScmVendor;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Modules\SupplyChain\Entities\ScmLcRecordLine;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScmLcRecord extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'lc_no',
        'lc_date',
        'expire_date',
        'weight',
        'no_of_packet',
        'scm_po_id',
        'invoice_value',
        'assessment_value',
        'issue_bank_id',
        'issue_bank_name',
        'advising_bank_id',
        'advising_bank_name',
        'discounting_bank_id',
        'discounting_bank_name',
        'beneficiary_bank_id',
        'beneficiary_bank_name',
        'scm_vendor_id',
        'scm_warehouse_id',
        'lc_type',
        'acc_bank_id',
        'bank_name',
        'cfr_value',
        'lc_margin',
        'total_cost',
        'document_value',
        'exchange_rate',
        'market_rate',
        'business_unit',
        'attachment',
        'created_by',
        'pi_ref_no',
        'expected_shipment_date',
        'shipment_date',
    ];

    public function scmLcRecordLines(): HasMany
    {
        return $this->hasMany(ScmLcRecordLine::class);
    }

    public function scmLcRecordStatuses(): HasMany
    {
        return $this->hasMany(ScmLcRecordStatus::class);
    }

    public function scmPo(): BelongsTo
    {
        return $this->belongsTo(ScmPo::class);
    }
    public function scmVendor(): BelongsTo
    {
        return $this->belongsTo(ScmVendor::class, 'scm_vendor_id', 'id');
    }
    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }
}
