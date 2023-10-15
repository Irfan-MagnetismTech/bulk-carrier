<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwSalaryStructureBreakdown extends Model
{
    use HasFactory;

	protected $fillable = ['component_type', 'component_category', 'component_name', 'amount'];
}
