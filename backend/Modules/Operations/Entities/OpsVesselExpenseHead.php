<?php

namespace Modules\Operations\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVesselExpenseHead extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'vessel_code',
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
}
