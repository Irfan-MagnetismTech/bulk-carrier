<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWoLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_wr_id',
        'scm_wo_id',
    ];

    public function scmWo(): BelongsTo
    {
        return $this->belongsTo(ScmWo::class);
    }

    public function scmWr(): BelongsTo
    {
        return $this->belongsTo(ScmWr::class);
    }

    public function scmWoItems(): HasMany
    {
        return $this->hasMany(ScmWoItem::class);
    }
}
