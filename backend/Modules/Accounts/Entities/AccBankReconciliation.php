<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccBankReconciliation extends Model
{
    use HasFactory;
    
	protected $fillable = ['acc_transaction_id', 'reconciliation_date', 'status', 'business_unit'];

}
