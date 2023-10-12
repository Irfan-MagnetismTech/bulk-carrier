<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MntItemGroup extends Model
{
    use HasFactory;

    protected $fillable = ['mnt_ship_department_id','name','short_code'];

    public function mntShipDepartment () : BelongsTo
    {
        return $this->belongsTo(MntShipDepartment::class);
    }
}
