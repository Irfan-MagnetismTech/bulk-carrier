<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWrrItem extends Model
{
    use HasFactory;

    protected $appends = ['amount'];

    protected $fillable = [
        'scm_wrr_id',
        'scm_wrr_line_id',
        'scm_service_id',
        'quantity',
        'rate',
        'wo_composite_key',
        'wr_composite_key',
        'net_rate',
    ];

    public function getAmountAttribute(): string
    {
        return $this->quantity * $this->rate;
    }

    public function scmWrrLine(): BelongsTo
    {
        return $this->belongsTo(ScmWrrLine::class);
    }

    public function scmService(): BelongsTo
    {
        return $this->belongsTo(ScmService::class);
    }

    public function scmWrLine(): BelongsTo
    {
        return $this->belongsTo(ScmWrLine::class, 'wr_composite_key', 'wr_composite_key');
    }

    public function scmWoLine(): BelongsTo
    {
        return $this->belongsTo(ScmWoLine::class, 'wo_composite_key', 'wo_composite_key');
    }

    public function scmWoItem(): BelongsTo
    {
        return $this->belongsTo(ScmWoItem::class, 'wo_composite_key', 'wo_composite_key');
    }
}
