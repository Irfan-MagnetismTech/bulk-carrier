<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmCsMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_cs_id', 'scm_material_id', 'quantity',
    ];
}
