<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVesselExpenseHead extends Model
{
    use HasFactory;

    protected $fillable = [
        'vessel_code',
        'ops_expense_head_id',
        'business_unit',
    ];

    public function opsExpenseHead()
    {
        return $this->belongsTo(OpsExpenseHead::class);
    }
}
