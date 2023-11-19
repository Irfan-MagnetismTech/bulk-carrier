<?php

namespace Modules\Crew\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\GlobalSearchTrait;

class CrwPolicy extends Model
{
    use HasFactory, GlobalSearchTrait;

	protected $fillable = ['name', 'type', 'attachment', 'business_unit'];
}
