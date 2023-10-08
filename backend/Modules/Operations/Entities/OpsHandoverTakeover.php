<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsHandoverTakeover extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [];
}
