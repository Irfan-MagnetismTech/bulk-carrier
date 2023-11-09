<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MntCriticalItemCat extends Model
{
    use HasFactory;

    protected $fillable = [
        'mnt_critical_function_id',
        'category_name',
        'notes'
    ];

    public function mntCriticalFunction() : BelongsTo {
        return $this->belongsTo(MntCriticalFunction::class);
    }

    public function mntCriticalItems() : HasMany {
        return $this->hasMany(MntCriticalItem::class);
    }
}
