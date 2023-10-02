<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwAgencyBillLine extends Model
{
    use HasFactory;

	protected $fillable = ['particular', 'description', 'per', 'quantity', 'rate', 'amount'];
}
