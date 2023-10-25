<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmMaterialCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'short_code','parent_id'
    ];
    
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'id', 'parent_id');
    }
}
