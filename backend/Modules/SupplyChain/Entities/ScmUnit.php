<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmUnit extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'name', 'short_code'
    ];
}
