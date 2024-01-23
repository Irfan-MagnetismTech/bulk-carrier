<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWoLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_wr_id',
        'scm_wo_id',
    ];
}
