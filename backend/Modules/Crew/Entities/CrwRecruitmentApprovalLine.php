<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwRecruitmentApprovalLine extends Model
{
    use HasFactory;

	protected $fillable = ['crw_rank_id', 'candidate_name', 'candidate_contact', 'candidate_email', 'remarks'];

    public function crwRank() : BelongsTo
    {
        return $this->belongsTo(CrwRank::class);
    }    
}
