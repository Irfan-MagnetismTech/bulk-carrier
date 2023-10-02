<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrewNominee extends Model
{
    use HasFactory;

    protected $fillable = ['name','profession','address','relationship','contact_no','email','is_relative'];
}
