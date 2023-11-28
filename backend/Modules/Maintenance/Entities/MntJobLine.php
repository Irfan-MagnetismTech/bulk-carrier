<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MntJobLine extends Model
{
    use HasFactory;

    protected $fillable = ['job_description','cycle','cycle_unit','min_limit','last_done','previous_run_hour','remarks','status'];
    protected $appends = ['mnt_job_line_id','next_due','over_due','present_run_hour','mnt_work_requisition_status'];
    protected $hidden = ['status'];
    
    
    public function mntJob () : BelongsTo
    {
        return $this->belongsTo(MntJob::class);
    }
    
    public function getMntWorkRequisitionStatusAttribute()
    {
        $statuses = $this->mntWorkRequisitionLines->pluck('mntWorkRequisitionItem.mntWorkRequisition.status')->unique();

        // Assuming that a job line can be related to multiple requisitions, we collect all unique statuses.
        return $statuses->last();
    }

    public function mntWorkRequisitionLines() : HasMany {
        return $this->hasMany(MntWorkRequisitionLine::class);
    }
    

    public function mntWorkRequisitionItem () : BelongsToMany
    {
        return $this->belongsToMany(MntWorkRequisitionItem::class, MntWorkRequisitionLine::class);
    }

    public function getNextDueAttribute()
    {
        if ($this->cycle_unit != 'Hours') {
            $nextDue = date('Y-m-d', strtotime($this->last_done. ' + '.$this->cycle.' '.$this->cycle_unit));
            
        } else {
            if ($this->present_run_hour > $this->cycle) {
                $hoursPassedAfterPreviousWork = $this->present_run_hour - $this->previous_run_hour;
                if ($this->cycle < $hoursPassedAfterPreviousWork) {
                    $nextDue = 0 - $hoursPassedAfterPreviousWork;
                } else {
                    $nextDue = $this->cycle - $hoursPassedAfterPreviousWork;//($this->present_run_hour % $this->cycle);
                }
                
            } else {
                $previousRunHour = $this->previous_run_hour ?? 0;
                $nextDue = ($this->cycle + $previousRunHour) - $this->present_run_hour;
            }
                
        }

        return $nextDue;
        
    }

    public function getOverDueAttribute()
    {
        if ($this->cycle_unit != 'Hours') {
            return strtotime(date('Y-m-d')) > strtotime($this->next_due);
        } else {
            return $this->present_run_hour > $this->cycle + $this->previous_run_hour;
            
        }
        
    }

    public function getPresentRunHourAttribute () 
    {
        return $this->mntJob->present_run_hour;
    }
    
    public function getMntJobLineIdAttribute () 
    {
        return $this->id;
    }
}
