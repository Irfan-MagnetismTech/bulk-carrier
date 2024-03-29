<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrewTraining extends Model
{
    use HasFactory;

    protected $fillable = ['training_title', 'covered_topic', 'year', 'institute', 'duration', 'location'];
}
