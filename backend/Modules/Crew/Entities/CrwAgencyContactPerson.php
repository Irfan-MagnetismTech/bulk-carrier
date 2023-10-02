<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwAgencyContactPerson extends Model
{
    use HasFactory;

	protected $fillable = ['name', 'contact_no', 'email', 'position', 'purpose'];
}
