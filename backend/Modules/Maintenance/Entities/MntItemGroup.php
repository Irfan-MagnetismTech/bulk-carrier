<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MntItemGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name','short_code'];
}
