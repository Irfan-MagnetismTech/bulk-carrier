<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\GlobalSearchTrait;

class AccTransaction extends Model
{
    use HasFactory, GlobalSearchTrait;

    /**
     * @var array
     */
    protected $fillable = ['acc_cost_center_id', 'voucher_type', 'transactionable_type', 'transactionable_id', 'transaction_date', 'bill_no', 'mr_no', 'narration', 'instrument_type', 'instrument_no', 'instrument_date', 'business_unit'];

    /**
     * @return mixed
     */
    public function ledgerEntries()
    {
        return $this->hasMany(AccLedgerEntry::class);
    }

    /**
     * @return mixed
     */
    public function bankReconciliation()
    {
        return $this->hasOne(AccBankReconciliation::class, 'acc_transaction_id', 'id');
    }

    public function costCenter()
    {
        return $this->hasOne(AccCostCenter::class, 'id', 'acc_cost_center_id');
    }

}
