<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Operations\Entities\OpsVessel;

class MntJob extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [];

    public function opsVessel () : BelongsTo
    {
        return $this->belongsTo(OpsVessel::class);
    }
    

    public function mntShipDepartment () : BelongsTo
    {
        return $this->belongsTo(MntShipDepartment::class);
    }
    
    public function mntItem () : BelongsTo
    {
        return $this->belongsTo(MntItem::class);
    }
}
