<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwVesselRequiredCrew extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [];
}
