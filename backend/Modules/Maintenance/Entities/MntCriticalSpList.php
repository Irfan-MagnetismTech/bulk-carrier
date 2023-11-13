<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Operations\Entities\OpsVessel;

class MntCriticalSpList extends Model
{
    use HasFactory;

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
}
