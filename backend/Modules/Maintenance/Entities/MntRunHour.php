<?php

namespace Modules\Maintenance\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Operations\Entities\OpsVessel;

class MntRunHour extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['ops_vessel_id','mnt_item_id','previous_run_hour','running_hour','present_run_hour','updated_on','business_unit'];

    public function opsVessel () : BelongsTo
    {
        return $this->belongsTo(OpsVessel::class);
    }
    
    public function mntItem () : BelongsTo
    {
        return $this->belongsTo(MntItem::class);
    }
}
