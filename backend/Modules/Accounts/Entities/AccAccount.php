<?php

namespace Modules\Accounts\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccAccount extends Model
{
    use HasFactory, GlobalSearchTrait;

    /**
     * @var array
     */
    protected $fillable = ['acc_balance_and_income_line_id', 'parent_account_id', 'account_name', 'account_code', 'account_type', 'accountable_type', 'accountable_id', 'official_code', 'is_archived', 'business_unit'];

    /**
     * @return mixed
     */
    public function balanceIncome()
    {
        return $this->belongsTo(AccBalanceAndIncomeLine::class, 'acc_balance_and_income_line_id', 'id');
    }

    /**
     * @return mixed
     */
    public function parent()
    {
        return $this->belongsTo(AccAccount::class, 'parent_account_id', 'id')->withDefault();
    }

    /**
     * @return mixed
     */
    public function childAccounts()
    {
        return $this->hasMany(AccAccount::class, 'parent_account_id', 'id');
    }

    /**
     * @return mixed
     */
    public function ledgers()
    {
        return $this->hasMany(AccLedgerEntry::class);
    }

    /**
     * @return mixed
     */
    public function accountOpeningBalance()
    {
        return $this->hasOne(AccAccountOpeningBalance::class, 'acc_account_id', 'id');
    }
}
