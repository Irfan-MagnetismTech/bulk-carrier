<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Modules\Operations\Entities\OpsVoyage;
use Modules\Operations\Entities\OpsExpenseHead;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVoyageExpenditureEntry extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_voyage_id',
        'ops_voyage_expenditure_id',
        'ops_expense_head_id',
        'port_code',
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

    /**
    * @var array
    */
    protected $skipForDeletionCheck = [''];
    
    /**
    * @var array
    */
    protected $features = [
    // 'relationName'           => 'Menu',
    ];
    
    public function opsExpenseHead()
    {
        return $this->belongsTo(OpsExpenseHead::class);
    }

    public function opsVoyage() {
        return $this->belongsTo(OpsVoyage::class);
    }

}
