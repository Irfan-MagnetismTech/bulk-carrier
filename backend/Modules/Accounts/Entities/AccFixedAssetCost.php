<?php

namespace Modules\Accounts\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccFixedAssetCost extends Model
{
    use HasFactory, GlobalSearchTrait;

    /**
     * @var array
     */
    protected $fillable = ['particular', 'amount', 'remarks'];

}
