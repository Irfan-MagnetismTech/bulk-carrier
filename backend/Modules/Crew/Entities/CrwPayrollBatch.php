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
    protected $fillable = ['ops_vessel_id', 'year_month', 'compensation_type', 'process_date', 'net_payment', 'currency', 'working_days', 'business_unit'];

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class);
    }

    public function crwPayrollBatchLines()
    {
        return $this->hasMany(CrwPayrollBatchLine::class);
    }

    public function crwPayrollBatchHeads()
    {
        return $this->hasMany(CrwPayrollBatchHead::class);
    }

    public function crwPayrollBatchHeadLines()
    {
        return $this->hasMany(CrwPayrollBatchHeadLine::class);
    }
}
