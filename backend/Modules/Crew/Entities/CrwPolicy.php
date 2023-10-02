<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrwPolicy extends Model
{
    use HasFactory;

	protected $fillable = ['name', 'type', 'attachment', 'business_unit'];
}
