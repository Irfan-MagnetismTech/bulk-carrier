<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MntItem extends Model
{
    use HasFactory;

    protected $fillable = ['mnt_ship_department_id', 'mnt_item_group_id', 'name', 'item_code', 'description', 'has_run_hour', 'present_run_hour'];
}
