<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MntJobLine extends Model
{
    use HasFactory;

    protected $fillable = ['job_description','cycle','cycle_unit','min_limit','last_done','next_due','remarks','status'];
    
    
    public function mntJob () : BelongsTo
    {
        return $this->belongsTo(MntJob::class);
    }
}
