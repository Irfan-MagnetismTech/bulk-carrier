<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_wo_line_id',
        'scm_service_id',
        'scm_wo_id',
        'description',
        'required_date',
        'quantity',
        'rate',
        'total_price',
        'wr_composite_key',
        'wo_composite_key',
        'wcs_composite_key',
        'closed_at',
        'closed_by',
        'is_closed',
        'closing_remarks',
        'status',
    ];

    public function scmWo(): BelongsTo
    {
        return $this->belongsTo(ScmService::class);
    }

    public function scmService(): BelongsTo
    {
        return $this->belongsTo(ScmService::class);
    }

    public function scmWrLine(): BelongsTo
    {
        return $this->belongsTo(ScmWrLine::class, 'wr_composite_key', 'wr_composite_key');
    }

    public function scmWcsService(): BelongsTo
    {
        return $this->belongsTo(ScmWcsService::class, 'wcs_composite_key', 'wcs_composite_key');
    }

    public function scmWoLine(): BelongsTo
    {
        return $this->belongsTo(ScmWoLine::class);
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by' , 'id');
    }
}
