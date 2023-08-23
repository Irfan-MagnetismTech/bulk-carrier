<?php

namespace Modules\Administration\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'signature',
    ];
}
