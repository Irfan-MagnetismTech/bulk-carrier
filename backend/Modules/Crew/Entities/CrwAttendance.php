<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\GlobalSearchTrait;
use Carbon\Carbon;
use Modules\Operations\Entities\OpsVessel;

class CrwAttendance extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $appends = ['month_year_name']; 

	protected $fillable = ['ops_vessel_id', 'year_month', 'working_days', 'total_crews', 'remarks', 'business_unit'];

	public function crwAttendanceLines(){
		return $this->hasMany(CrwAttendanceLine::class);
	}

	public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class);
    }
	
    public function crwPayrollBatch()
    {
        return $this->hasOne(CrwPayrollBatch::class);
    }

    public function getMonthYearNameAttribute(){
        return Carbon::createFromFormat('Y-m', $this->year_month)->format('F, Y');
    }
}
