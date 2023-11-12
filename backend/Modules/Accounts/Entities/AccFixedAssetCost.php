<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccFixedAssetCost extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['particular', 'amount'];

}
