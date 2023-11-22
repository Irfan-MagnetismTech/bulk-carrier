<?php

namespace Modules\Accounts\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccLoanReceived extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['acc_loan_id', 'received_date', 'payment_method', 'received_acc_account_id', 'instrument_no', 'instrument_date', 'received_amount', 'interest_rate'];
}
