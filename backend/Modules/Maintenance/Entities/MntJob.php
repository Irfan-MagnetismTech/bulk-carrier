<?php

namespace Modules\Maintenance\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Operations\Entities\OpsVessel;

class MntJob extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = ['ops_vessel_id','mnt_item_id','present_run_hour','business_unit'];
    protected $appends = ['has_work_requisition'];

    protected $skipForDeletionCheck = ['mntJobLines'];

    protected $features = [
        'mntWorkRequisitionLines' => 'Work Requisitions',
    ];

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

    public function mntWorkRequisitionLines() : HasManyThrough {
        return $this->hasManyThrough(MntWorkRequisitionLine::class, MntJobLine::class);
    }

    public function getHasWorkRequisitionAttribute() {
        return $this->mntWorkRequisitionLines()->count() > 0;
    }
}
