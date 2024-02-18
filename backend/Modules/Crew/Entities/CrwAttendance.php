<?php

namespace Modules\Crew\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\GlobalSearchTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Operations\Entities\OpsVessel;

class CrwAttendance extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $appends = ['month_year_name']; 

	protected $fillable = ['ops_vessel_id', 'year_month', 'working_days', 'total_crews', 'remarks', 'business_unit'];

        /**
     * @var array
     */
    protected $skipForDeletionCheck = ['crwAttendanceLines', 'opsVessel'];

    /**
     * @var array
     */
    protected $features = [
        'crwPayrollBatch'     => 'Salary',
    ];

    /* ------------------------- Associate Relationship Start ------------------------- */
	public function crwAttendanceLines() : HasMany
    {
		return $this->hasMany(CrwAttendanceLine::class);
	}
    /* ------------------------- Associate Relationship End ------------------------- */


    /* -------------------------  Belongs Relationship Start ------------------------- */
    public function opsVessel() : BelongsTo
    {
        return $this->belongsTo(OpsVessel::class);
    }
    /* -------------------------  Belongs Relationship End ------------------------- */


	/* -------------------------  Has Many Relationship Start ------------------------- */
    public function crwPayrollBatch() : HasOne
    {
        return $this->hasOne(CrwPayrollBatch::class);
    }
	/* -------------------------  Has Many Relationship End ------------------------- */

    public function getMonthYearNameAttribute(){
        return Carbon::createFromFormat('Y-m', $this->year_month)->format('F, Y');
    }

}
