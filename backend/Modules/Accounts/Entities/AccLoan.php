<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccLoan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['business_unit'];
}