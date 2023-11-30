<?php

namespace Modules\Maintenance\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Modules\Operations\Entities\OpsVessel;
use Ramsey\Uuid\Type\Integer;

class MntWorkRequisition extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'reference_no',
        'ops_vessel_id',
        'assigned_to',
        'responsible_person',
        'scm_vendor_id',
        'maintenance_type',
        'requisition_date',
        'est_start_date',
        'est_completion_date',
        'act_start_date',
        'act_completion_date',
        'status',
        'business_unit'
    ];

    protected $casts = ['status'=>'integer'];
    
    public function opsVessel () : BelongsTo
    {
        return $this->belongsTo(OpsVessel::class);
    }

    public function mntWorkRequisitionItem() : HasOne {
        return $this->hasOne(MntWorkRequisitionItem::class);
    }

    public function mntWorkRequisitionLines() : HasManyThrough {
        return $this->hasManyThrough(MntWorkRequisitionLine::class, MntWorkRequisitionItem::class);
    }

    public function mntWorkRequisitionMaterials() : HasMany {
        return $this->hasMany(MntWorkRequisitionMaterial::class);
    }
}
