<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MntCriticalItemSp extends Model
{
    use HasFactory;

    protected $fillable = [
        'mnt_critical_vessel_item_id',
        'sp_name',
        'unit',
        'min_rob'
    ];
    
    public function mntCriticalVesselItem () : BelongsTo
    {
        return $this->belongsTo(MntCriticalVesselItem::class);
    }
}
