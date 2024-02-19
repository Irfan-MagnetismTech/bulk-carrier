<?php

namespace Modules\Crew\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrwBankAccount extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

	protected $fillable = ['crw_crew_id', 'bank_name', 'branch_name', 'routing_number', 'account_name', 'account_number', 'benificiary_name', 'attachment', 'is_active', 'business_unit'];

    protected $skipForDeletionCheck = ['crwCrewProfile'];

    /**
     * @var array
     */
    protected $features = [
        'crwPayrollBatchLines' => 'Salary',
    ];

    /* ------------------------- Associate Relationship Start ------------------------- */
    /* ------------------------- Associate Relationship End ------------------------- */

    /* -------------------------  Belongs Relationship Start ------------------------- */
    public function crwCrewProfile() : BelongsTo
    {
        return $this->belongsTo(CrwCrewProfile::class, 'crw_crew_id', 'id');
    }
    /* -------------------------  Belongs Relationship End ------------------------- */
	
	/* -------------------------  Has Many Relationship Start ------------------------- */
    public function crwPayrollBatchLines(): HasMany
    {
        return $this->hasMany(CrwPayrollBatchLine::class, 'crw_bank_account_id', 'id');
    }    
	/* -------------------------  Has Many Relationship End ------------------------- */    
}
