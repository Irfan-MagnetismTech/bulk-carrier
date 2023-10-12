<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MntItem extends Model
{
    use HasFactory;

    protected $fillable = ['mnt_ship_department_id', 'mnt_item_group_id', 'name', 'item_code', 'description', 'has_run_hour', 'present_run_hour'];
    protected $casts = [
        'has_run_hour' => 'boolean',
    ];
    

    public function mntShipDepartment () : BelongsTo
    {
        return $this->belongsTo(MntShipDepartment::class);
    }

    
    public function mntItemGroup () : BelongsTo
    {
        return $this->belongsTo(MntItemGroup::class);
    }
}
