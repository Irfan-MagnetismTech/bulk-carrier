<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class AccBalanceAndIncomeLine extends Model
{
    use HasFactory, NodeTrait;

    /**
     * @var array
     */
    protected $fillable = ['line_type','line_text','value_type','parent_id','visible_index','printed_no','business_unit'];

    /**
     * @return mixed
     */
    public function parentLine()
    {
        return $this->belongsTo(AccBalanceAndIncomeLine::class, 'parent_id', 'id')->withDefault();
    }

    /**
     * @return mixed
     */
    public function accounts()
    {
        return $this->hasMany(AccAccount::class, 'acc_balance_and_income_line_id', 'id');
    }

    /**
     * @return mixed
     */
    public function parentAccounts()
    {
        return $this->hasMany(AccAccount::class, 'acc_balance_and_income_line_id', 'id')->whereNull('parent_account_id');
    }

    /**
     * @return mixed
     */
    public function ledgers()
    {
        return $this->hasMany(AccLedgerEntry::class);
    }
}
