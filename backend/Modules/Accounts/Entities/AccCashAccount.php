<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccCashAccount extends Model
{
    use HasFactory;

	protected $fillable = ['name', 'business_unit'];
}
