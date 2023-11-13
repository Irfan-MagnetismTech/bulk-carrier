<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MntCriticalSpListLine extends Model
{
    use HasFactory;

    protected $fillable = [
        "mnt_critical_item_sp_id",
        "min_rob",
        "rob",
        "remarks"
    ];

    public function mntCriticalSpList() : BelongsTo {
        return $this->belongsTo(MntCriticalSpList::class);
    }

    public function mntCriticalItemSp() : BelongsTo {
        return $this->belongsTo(MntCriticalItemSp::class);
    }
}
