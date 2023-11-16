<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccLedgerEntry extends Model
{
    use HasFactory;

	protected $fillable = ['acc_transaction_id', 'acc_balance_and_income_line_id', 'acc_cost_center_id', 'acc_account_id', 'dr_amount', 'cr_amount', 'ref_bill', 'bill_status', 'purpose', 'remarks'];

    public function account()
    {
        return $this->belongsTo(AccAccount::class, 'acc_account_id', 'id')->withDefault();
    }

    public function transaction()
    {
        return $this->belongsTo(AccTransaction::class, 'acc_transaction_id', 'id')->withDefault();
    }
}
