<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWrLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_wr_id',
        'scm_service_id',
        'quantity',
        'required_date',
        'description',
        'remarks',
        'closed_at',
        'closed_by',
        'created_by',
        'is_closed',
        'closing_remarks',
        'status',
        'wr_composite_key',
    ];

    public function scmWr()
    {
        return $this->belongsTo(ScmWr::class, 'scm_wr_id', 'id');
    }

    public function scmService(): BelongsTo
    {
        return $this->belongsTo(ScmService::class);
    }

    // public function scmWcsServices(): HasMany
    // {
    //     return $this->hasMany(ScmWcsService::class, 'scm_wr_id', 'scm_wr_id');
    // }

    public function scmWcsServices(): HasMany
    {
        return $this->hasMany(ScmWcsService::class, 'wr_composite_key', 'wr_composite_key');
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
