<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrewDocumentRenewal extends Model
{
    use HasFactory;

	protected $fillable = ['issue_date', 'expire_date', 'reference_no', 'attachment'];
}
