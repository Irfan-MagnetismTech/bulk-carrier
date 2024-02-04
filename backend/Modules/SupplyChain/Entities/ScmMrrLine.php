<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Entities\ScmMrr;
use Modules\SupplyChain\Entities\ScmMrrLineItem;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmMrrLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_pr_id',
        'scm_po_id'
    ];

    public function scmMrr(): BelongsTo
    {
        return $this->belongsTo(ScmMrr::class);
    }

    public function scmPr(): BelongsTo
    {
        return $this->belongsTo(ScmPr::class);
    }

    public function scmMrrLineItems(): HasMany
    {
        return $this->hasMany(ScmMrrLineItem::class);
    }
}
