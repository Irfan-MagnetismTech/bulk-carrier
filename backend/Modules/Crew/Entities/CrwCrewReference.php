<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrewReference extends Model
{
    use HasFactory;

    protected $fillable = ['name','organization','designation','address','contact_office','contact_personal','email','relation'];
}
