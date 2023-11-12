<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Operations\Entities\OpsVessel;

class MntJob extends Model
{
    use HasFactory;

    protected $fillable = ['ops_vessel_id','mnt_item_id','present_run_hour','business_unit'];

    public function opsVessel () : BelongsTo
    {
        return $this->belongsTo(OpsVessel::class);
    }
    
    public function mntItem () : BelongsTo
    {
        return $this->belongsTo(MntItem::class);
    }

    public function mntJobLines () : HasMany
    {
        return $this->hasMany(MntJobLine::class);
    }
}
