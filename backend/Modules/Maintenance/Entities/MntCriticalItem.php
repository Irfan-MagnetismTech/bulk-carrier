<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class MntCriticalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'mnt_critical_item_cat_id',
        'item_name',
        'specification',
        'notes'
    ];

    public function mntCriticalItemCat() : BelongsTo {
        return $this->belongsTo(MntCriticalItemCat::class);
    }

    public function mntCriticalVesselItems() : HasMany {
        return $this->hasMany(MntCriticalVesselItem::class);
    }

    public function mntCriticalItemSps() : HasManyThrough {
        return $this->hasManyThrough(MntCriticalItemSp::class, MntCriticalVesselItem::class);
    }
}
