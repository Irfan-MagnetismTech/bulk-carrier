<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmPoLine;
use Modules\SupplyChain\Entities\ScmPrLine;
use Modules\SupplyChain\Entities\ScmMaterial;
use Modules\SupplyChain\Entities\ScmCsMaterial;
use Modules\SupplyChain\Entities\ScmMrrLineItem;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmPoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_po_id',
        'scm_po_line_id',
        'scm_material_id',
        'unit',
        'brand',
        'model',
        'required_date',
        'quantity',
        'rate',
        'total_price',
        'net_rate',
        'po_composite_key',
        'pr_composite_key',
        'cs_composite_key',
        'tolarence_level',
        'remarks',
        'is_closed',
        'closed_by',
        'closed_at',
        'closing_remarks',
        'status',
    ];

    public function scmMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmMaterial::class);
    }

    public function scmPrLine(): BelongsTo
    {
        return $this->belongsTo(ScmPrLine::class, 'pr_composite_key', 'pr_composite_key');
    }

    // public function scmMrrLines(): HasMany
    // {
    //     return $this->hasMany(ScmMrrLine::class, 'po_composite_key', 'po_composite_key');
    // }

    public function scmCsMaterial(): BelongsTo
    {
        return $this->belongsTo(ScmCsMaterial::class, 'cs_composite_key', 'cs_composite_key');
    }

    public function scmPoLine(): BelongsTo
    {
        return $this->belongsTo(ScmPoLine::class);
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function scmMrrLineItems(): HasMany
    {
        return $this->hasMany(ScmMrrLineItem::class, 'po_composite_key', 'po_composite_key');
    }
}
