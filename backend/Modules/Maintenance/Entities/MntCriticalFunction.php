<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MntCriticalFunction extends Model
{
    use HasFactory;

    protected $fillable = ['function_name','notes'];

    public function mntCriticalItemCats() : HasMany {
        return $this->hasMany(MntCriticalItemCat::class);
    }
}
