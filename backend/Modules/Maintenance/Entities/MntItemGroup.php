<?php

namespace Modules\Maintenance\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MntItemGroup extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['mnt_ship_department_id','name','short_code','business_unit'];

    protected $skipForDeletionCheck = [];

    protected $features = [
        'mntItems' => 'Items',
    ];

    public function mntShipDepartment () : BelongsTo
    {
        return $this->belongsTo(MntShipDepartment::class);
    }

    public function mntItems () : HasMany
    {
        return $this->hasMany(MntItem::class);
    }
}
