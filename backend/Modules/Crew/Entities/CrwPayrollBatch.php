<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrwPayrollBatch extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['ops_vessel_id', 'month_no', 'year', 'compensation_type', 'start_date', 'end_date', 'process_date', 'net_payment', 'currency', 'working_days', 'business_unit'];
}
