<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\GlobalSearchTrait;

class AccCashAccount extends Model
{
    use HasFactory, GlobalSearchTrait;

	protected $fillable = ['name', 'business_unit'];
}
