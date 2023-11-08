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
        'job_description',
        'cycle',
        'cycle_unit',
        'min_limit',
        'last_done',
        'previous_run_hour',
        'present_run_hour',
        'status',
        'start_date',
        'completion_date',
        'checking',
        'replace',
        'cleaning',
        'remarks'
    ];

    protected $casts = [
        'checking' => 'boolean',
        'replace' => 'boolean',
        'cleaning' => 'boolean',
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
