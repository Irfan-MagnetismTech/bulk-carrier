<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmPoTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_po_id', 'description'
    ];
}
