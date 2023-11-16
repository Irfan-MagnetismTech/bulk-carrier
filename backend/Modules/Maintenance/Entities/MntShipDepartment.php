<?php

namespace Modules\Maintenance\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MntShipDepartment extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['name','short_code','business_unit'];

    public function mntItemGroups () : HasMany
    {
        return $this->hasMany(MntItemGroup::class);
    }
}
