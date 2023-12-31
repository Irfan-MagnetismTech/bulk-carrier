<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVoyageExpenditureEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_voyage_id',
        'ops_voyage_expenditure_id',
        'ops_expense_head_id',
        'particular',
        'type',
        'invoice_date',
        'invoice_no',
        'currency',
        'quantity',
        'rate',
        'amount',
        'amount_bdt',
    ];

    public function opsExpenseHead()
    {
        return $this->belongsTo(OpsExpenseHead::class);
    }


}
