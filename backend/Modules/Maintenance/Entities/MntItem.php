<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MntItem extends Model
{
    use HasFactory;

    protected $fillable = ['mnt_item_group_id', 'name', 'item_code', 'description', 'has_run_hour', 'business_unit'];
    protected $casts = [
        'has_run_hour' => 'boolean',
    ];
    

    
    public function mntItemGroup () : BelongsTo
    {
        return $this->belongsTo(MntItemGroup::class);
    }

    public function mntJobs () : HasMany
    {
        return $this->hasMany(MntJob::class);
    }
}
