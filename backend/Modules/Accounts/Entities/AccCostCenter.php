<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccCostCenter extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['name', 'short_name', 'business_unit', 'type'];

}
