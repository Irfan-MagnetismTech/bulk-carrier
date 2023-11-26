<?php

namespace Modules\Accounts\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccLoan extends Model
{
    use HasFactory, GlobalSearchTrait;
    
    protected $appends = ['total_received_amount'];

    protected $fillable = ['loanable_type','loanable_id','loan_type','loan_number','loan_name','total_sanctioned','sanctioned_limit','total_installment','interest_rate','opening_date','maturity_date','emi_date','emi_amount','loan_purpose','mortgage','remarks','business_unit'];


    public function accLoansReceived(){
        return $this->hasMany(AccLoanReceived::class, 'acc_loan_id', 'id'); 
    }

    public function getTotalReceivedAmountAttribute(){
        return $this->accLoansReceived()->sum('received_amount'); 
    }

    public function bank()
    {
        return $this->belongsTo(AccBankAccount::class, 'loanable_id', 'id')->withDefault();
    }
}
