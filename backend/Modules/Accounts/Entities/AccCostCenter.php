<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GlobalSearchTrait;

class AccCostCenter extends Model
{
    use HasFactory, GlobalSearchTrait;

    /**
     * @var array
     */
    protected $fillable = ['ops_vessel_id', 'name', 'short_name', 'business_unit', 'type'];

}
