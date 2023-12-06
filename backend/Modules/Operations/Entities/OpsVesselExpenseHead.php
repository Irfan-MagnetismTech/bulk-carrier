<?php

namespace Modules\Operations\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Operations\Entities\OpsVessel;
use Modules\Operations\Entities\OpsExpenseHead;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVesselExpenseHead extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'ops_vessel_id',
        'ops_expense_head_id',
        'business_unit',
    ];

    // public function opsVessel()
    // {
    //     return $this->belongsTo(OpsVessel::class,'vessel_code','short_code');
    // }

    public function opsExpenseHead()
    {
        return $this->belongsTo(OpsExpenseHead::class);
    }

    public function opsVessel() {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id');
    }
}
