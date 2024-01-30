<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LastInserted extends Model
{
    protected $table = 'last_inserted';

    protected $fillable = ['last_inserted_id', 'last_inserted_type'];

    public function trackable(): MorphTo
    {
        return $this->morphTo();
    }
}
