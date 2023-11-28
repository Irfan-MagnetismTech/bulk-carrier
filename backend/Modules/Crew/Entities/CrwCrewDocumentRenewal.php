<?php

namespace Modules\Crew\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrewDocumentRenewal extends Model
{
    use HasFactory;

	protected $fillable = ['issue_date', 'expire_date', 'reference_no', 'attachment', 'crw_crew_document_id'];
    protected $appends = ['left_days']; 


    public function getLeftDaysAttribute()
    {
        $expireDate = Carbon::parse($this->expire_date);
        $today = Carbon::today();

        return $today->diffInDays($expireDate, false);
    }

}
