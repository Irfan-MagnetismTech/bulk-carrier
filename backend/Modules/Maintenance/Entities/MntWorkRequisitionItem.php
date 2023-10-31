<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class MntWorkRequisitionItem extends Model
{
    use HasFactory;

    protected $fillable = ['mnt_work_requisition_id','mnt_item_id'];
    
    public function mntWorkRequisition () : BelongsTo
    {
        return $this->belongsTo(MntWorkRequisition::class);
    }
    
    public function mntItem () : BelongsTo
    {
        return $this->belongsTo(MntItem::class);
    }

    public function mntWorkRequisitionLines () : HasMany
    {
        return $this->hasMany(MntWorkRequisitionLine::class);
    }

    public function mntJobLines () : BelongsToMany
    {
        return $this->belongsToMany(MntJobLine::class, MntWorkRequisitionLine::class);
    }
}
