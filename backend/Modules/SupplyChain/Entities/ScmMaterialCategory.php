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

    // public function getTopLevelParent(): ?self
    // {
    //     $currentCategory = $this;
        
    //     while ($currentCategory->parent) {
    //         $currentCategory = $currentCategory->parent;
    //     }

    //     return $currentCategory;
    // }

    public function scopeTopLevelParent($query, $categoryId)
    {
        $category = $query->find($categoryId);

        while ($category && $category->parent) {
            $category = $category->parent;
        }

        return $category;
    }

    public function getAllDescendants(): HasMany
    {
        return $this->children()->with('getAllDescendants');
    }

    public function isLeafNode(): bool
    {
        return $this->children()->count() === 0;
    }

    public function getAllSiblings(): HasMany
    {
        return $this->parent->children()->where('id', '!=', $this->id);
    }


    public function getAllAncestors(): BelongsTo
    {
        return $this->parent()->with('getAllAncestors');
    }

    public function account(){
        return $this->morphOne(AccAccount::class, 'accountable')->withDefault();
    }
}
