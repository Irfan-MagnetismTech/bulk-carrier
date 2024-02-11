<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmCosting;
use Modules\SupplyChain\Entities\ScmLcRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmCostingLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_costing_id',
        'scm_lc_record_id',
        'particulars',
        'exchange_rate',
        'bdt_amount',
        'usd_amount',
        'type'
    ];


    public function scmLcRecord()
    {
        return $this->belongsTo(ScmLcRecord::class);
    }

    public function scmCosting()
    {
        return $this->belongsTo(ScmCosting::class);
    }
}
