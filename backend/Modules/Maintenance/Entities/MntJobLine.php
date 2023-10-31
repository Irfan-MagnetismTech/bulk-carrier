<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MntJobLine extends Model
{
    use HasFactory;

    protected $fillable = ['job_description','cycle','cycle_unit','min_limit','last_done','previous_run_hour','remarks','status'];
    protected $appends = ['mnt_job_line_id','next_due','over_due','present_run_hour'];
    
    
    public function mntJob () : BelongsTo
    {
        return $this->belongsTo(MntJob::class);
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
            $nextDue = ($this->present_run_hour > $this->cycle) 
                            ? $this->present_run_hour % $this->cycle
                            : $this->cycle - $this->present_run_hour;
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
