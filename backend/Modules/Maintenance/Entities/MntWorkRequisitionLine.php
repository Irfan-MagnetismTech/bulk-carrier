<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MntWorkRequisitionLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'mnt_work_requisition_item_id',
        'mnt_job_line_id',
        'last_done',
        'running_hour',
        'status',
        'start_date',
        'completion_date'
    ];
    
    public function mntWorkRequisitionItem () : BelongsTo
    {
        return $this->belongsTo(MntWorkRequisitionItem::class);
    }
    
    public function mntJobLine () : BelongsTo
    {
        return $this->belongsTo(MntJobLine::class);
    }
}
