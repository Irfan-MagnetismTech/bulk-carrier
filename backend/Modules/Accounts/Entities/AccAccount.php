<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccAccount extends Model
{
    use HasFactory;
    
	protected $fillable = ['acc_balance_and_income_line_id','parent_account_id','account_name','account_code','account_type','accountable_type','accountable_id','official_code','is_archived','business_unit'];

    public function balanceIncome()
    {
        return $this->belongsTo(AccBalanceAndIncomeLine::class, 'acc_balance_and_income_line_id', 'id');
    }
    
    public function parent()
    {
        return $this->belongsTo(AccAccount::class, 'parent_account_id', 'id')->withDefault();
    }

    public function childAccounts()
    {
        return $this->hasMany(AccAccount::class, 'parent_account_id', 'id');
    }

    public function ledgers()
    {
        return $this->hasMany(AccLedgerEntry::class);
    }

    public function accountOpeningBalance()
    {
        return $this->hasOne(AccAccountOpeningBalance::class, 'acc_account_id', 'id');
    } 
    
    
}
