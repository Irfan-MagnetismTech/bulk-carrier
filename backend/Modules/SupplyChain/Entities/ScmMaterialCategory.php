<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScmMaterialCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'short_code','parent_id'
    ];
}
