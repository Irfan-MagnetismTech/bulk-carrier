<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccAdvanceAdjustmentLine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [];
}