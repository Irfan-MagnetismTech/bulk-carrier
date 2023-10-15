<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwRank extends Model
{
    use HasFactory;

	protected $fillable = ['name', 'short_name', 'business_unit'];
}
