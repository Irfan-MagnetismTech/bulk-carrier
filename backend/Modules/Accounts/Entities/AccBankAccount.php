<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GlobalSearchTrait;

class AccBankAccount extends Model
{
    use HasFactory, GlobalSearchTrait;

    /**
     * @var array
     */
    protected $fillable = ['bank_name','branch_name','account_type','account_name','account_number','routing_number','contact_number','opening_date','opening_balance','business_unit'];
    
}
