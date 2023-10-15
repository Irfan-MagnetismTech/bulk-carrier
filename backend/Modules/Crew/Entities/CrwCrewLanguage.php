<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwCrewLanguage extends Model
{
    use HasFactory;

    protected $fillable = ['language_name', 'writing', 'reading', 'speaking', 'listening'];
}
