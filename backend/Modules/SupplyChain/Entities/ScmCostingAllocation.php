<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScmCostingAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_costing_id',
        'scm_mrr_id',
        'value',
        'allocated_amount',
    ];

    public function scmMrr(): BelongsTo
    {
        return $this->belongsTo(ScmMrr::class);
    }

}
