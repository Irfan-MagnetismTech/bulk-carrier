<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GlobalSearchTrait;

class AccBankAccount extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $appends = ['bank_name_type'];


    /**
     * @var array
     */
    protected $fillable = ['bank_name','branch_name','account_type','account_name','account_number','routing_number','contact_number','opening_date','opening_balance','business_unit'];


    public function getBankNameTypeAttribute(){
        return $this->bank_name.  ' - ' .$this->account_type; 
    }
    
}
