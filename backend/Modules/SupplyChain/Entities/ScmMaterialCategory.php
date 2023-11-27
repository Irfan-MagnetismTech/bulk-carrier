<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Accounts\Entities\AccAccount;

class ScmMaterialCategory extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'name', 'short_code','parent_id'
    ];
    
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'id', 'parent_id');
    }

    public function account(){
        return $this->morphOne(AccAccount::class, 'accountable')->withDefault();
       }
}
