<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccAccountOpeningBalance extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['acc_cost_center_id', 'acc_account_id', 'date', 'dr_amount', 'cr_amount', 'business_unit'];

    //has one account
    /**
     * @return mixed
     */
    public function account()
    {
        return $this->hasOne(AccAccount::class, 'id', 'acc_account_id');
    }
}
