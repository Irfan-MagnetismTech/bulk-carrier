<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmWrLine;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWcsService extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_wr_id',
        'scm_wcs_id',
        'scm_service_id',
        'quantity',
        'wcs_composite_key',
        'wr_composite_key'
    ];

    public function scmWr()
    {
        return $this->belongsTo(ScmWr::class, 'scm_wr_id', 'id');
    }

    public function scmWcs()
    {
        return $this->belongsTo(ScmWcs::class, 'scm_wcs_id', 'id');
    }

    public function scmService()
    {
        return $this->belongsTo(ScmService::class, 'scm_service_id', 'id');
    }

    public function scmWrLine(): BelongsTo
    {
        return $this->belongsTo(ScmWrLine::class, 'wr_composite_key', 'wr_composite_key');
    }

    public function scmWoItems(): HasMany
    {
        return $this->hasMany(ScmWoItem::class, 'wr_composite_key', 'wr_composite_key');
    }
}
