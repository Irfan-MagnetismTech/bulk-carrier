<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Operations\Entities\OpsVessel;

class CrwPayrollBatch extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['ops_vessel_id', 'month_no', 'year', 'compensation_type', 'start_date', 'end_date', 'process_date', 'net_payment', 'currency', 'working_days', 'business_unit'];

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class);
    }

    public function crwPayrollBatchLines()
    {
        return $this->belongsTo(CrwPayrollBatchLine::class);
    }

    public function crwPayrollBatchHeads()
    {
        return $this->belongsTo(CrwPayrollBatchHead::class);
    }

    public function crwPayrollBatchHeadLines()
    {
        return $this->belongsTo(CrwPayrollBatchHeadLine::class);
    }
}
