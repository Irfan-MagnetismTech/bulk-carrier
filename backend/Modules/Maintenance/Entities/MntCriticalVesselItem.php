<?php

namespace Modules\Maintenance\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Operations\Entities\OpsVessel;

class MntCriticalVesselItem extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'ops_vessel_id',
        'mnt_critical_item_id',
        'is_critical',
        'specification',
        'notes',
        'business_unit'
    ];

    protected $casts = ['is_critical' => 'boolean'];
    
    public function opsVessel () : BelongsTo
    {
        return $this->belongsTo(OpsVessel::class);
    }
    
    public function mntCriticalItem () : BelongsTo
    {
        return $this->belongsTo(MntCriticalItem::class);
    }

    public function mntCriticalItemSps() : HasMany {
        return $this->hasMany(MntCriticalItemSp::class);
    }

    public function mntCriticalSpListLines() : HasMany {
        return $this->hasMany(MntCriticalSpListLine::class);
    }
}
