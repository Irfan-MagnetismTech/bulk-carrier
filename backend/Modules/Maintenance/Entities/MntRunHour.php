<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MntRunHour extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [];
}
