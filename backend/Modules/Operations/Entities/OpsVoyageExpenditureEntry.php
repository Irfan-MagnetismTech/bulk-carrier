<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVoyageExpenditureEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_voyage_expenditure_id',
        'particular',
        'type',
        'invoice_date',
        'invoice_no',
        'currency',
        'rate',
        'amount',
        'amount_bdt',
        'attachment',
        'remarks',
    ];
}
