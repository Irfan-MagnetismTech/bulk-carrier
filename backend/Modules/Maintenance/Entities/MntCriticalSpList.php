<?php

namespace Modules\Maintenance\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Modules\Operations\Entities\OpsVessel;

class MntCriticalSpList extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        "ops_vessel_id",
        "reference_no",
        "record_date",
        "business_unit",
    ];
    
    public function opsVessel () : BelongsTo
    {
        return $this->belongsTo(OpsVessel::class);
    }

    public function mntCriticalSpListLines() : HasMany {
        return $this->hasMany(MntCriticalSpListLine::class);
    }

    public function mntCriticalVesselItems()
    {
        return $this->hasMany(MntCriticalVesselItem::class, 'ops_vessel_id', 'ops_vessel_id')->where('is_critical',1);
    }

}
