<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_wo_id',
        'description',
    ];
}
