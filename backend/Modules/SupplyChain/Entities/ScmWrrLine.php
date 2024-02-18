<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWrrLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_wrr_id',
        'scm_wr_id',
        'scm_wo_id',
    ];

    public function scmWrr(): BelongsTo
    {
        return $this->belongsTo(ScmWrr::class);
    }

    public function scmWr(): BelongsTo
    {
        return $this->belongsTo(ScmWr::class);
    }

    public function scmWrrLineItems(): HasMany
    {
        return $this->hasMany(ScmWrrItem::class);
    }
}
