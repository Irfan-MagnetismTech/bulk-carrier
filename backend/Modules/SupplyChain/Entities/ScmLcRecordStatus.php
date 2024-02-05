<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmLcRecordStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_lc_record_id',
        'date',
        'status',
        'remarks',
    ];

    
}
