<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccCashRequisitionLine extends Model
{
    use HasFactory;

    protected $fillable = ['particular', 'remarks', 'amount'];

}
