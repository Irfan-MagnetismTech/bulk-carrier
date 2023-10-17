<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MntShipDepartment extends Model
{
    use HasFactory;

    protected $fillable = ['name','short_code','business_unit'];

    public function mntItem () : HasMany
    {
        return $this->hasMany(MntItem::class);
    }

    public function mntItemGroup () : HasMany
    {
        return $this->hasMany(MntItemGroup::class);
    }
}
