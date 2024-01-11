<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWr extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_warehouse_id',
        'acc_cost_center_id',
        'raised_date',
        'raised_date',
        'approved_date',
        'attachment',
        'remarks',
        'business_unit',
    ];

    public function scmWrLines(): HasMany
    {
        return $this->hasMany(ScmWrLine::class)->latest();
    }
}
