<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'short_code',
    ];
}
