<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwAttendance extends Model
{
    use HasFactory;

	protected $fillable = ['ops_vessel_id', 'year', 'month_no', 'working_days', 'business_unit'];

	public function crwAttendanceLines(){
		return $this->hasMany(CrwAttendanceLine::class);
	}
}
