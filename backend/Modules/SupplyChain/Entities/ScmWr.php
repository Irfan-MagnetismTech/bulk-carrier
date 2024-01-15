<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScmWr extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'scm_warehouse_id',
        'acc_cost_center_id',
        'raised_date',
        'raised_date',
        'approved_date',
        'attachment',
        'remarks',
        'business_unit',
        'closed_at',
        'closed_by',
        'is_closed',
        'closing_remarks',
        'status',
    ];

    public function scmWrLines(): HasMany
    {
        return $this->hasMany(ScmWrLine::class)->latest();
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by' , 'id');
    }
}
