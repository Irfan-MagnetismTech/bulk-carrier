<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MntRunHour extends Model
{
    use HasFactory;

    protected $fillable = ['ops_vessel_id','mnt_item_id','previous_run_hour','present_run_hour','updated_on','business_unit'];
}
