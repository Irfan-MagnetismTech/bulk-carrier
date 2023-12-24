<?php

namespace Modules\Maintenance\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\DeletableModel;

class MntCriticalFunction extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = ['function_name','notes'];

    public function mntCriticalItemCats() : HasMany {
        return $this->hasMany(MntCriticalItemCat::class);
    }
}
