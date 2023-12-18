<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccSalaryLine extends Model
{
    use HasFactory;

    protected $fillable = ['particular', 'amount'];
}
