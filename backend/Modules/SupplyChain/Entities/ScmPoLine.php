<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScmPoLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_po_id', 'scm_pr_id'
    ];

    public function scmPo(): BelongsTo
    {
        return $this->belongsTo(ScmPo::class);
    }

    public function scmPr(): BelongsTo
    {
        return $this->belongsTo(ScmPr::class);
    }

    public function scmPoItems(): HasMany
    {
        return $this->hasMany(ScmPoItem::class);
    }

}
