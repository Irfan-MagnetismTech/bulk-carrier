<?php

namespace Modules\Accounts\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccSalaryHead extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['name', 'business_unit'];
}
