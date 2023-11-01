<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsMonthWiseExpenseReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_vessel_id',
        'from_date',
        'till_date',
        'grand_total_cost',
        'left_over_amount',
        'remarks',
        'business_unit',
    ];

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }
}
