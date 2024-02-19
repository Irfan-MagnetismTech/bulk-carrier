<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsExpenseHead extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'billing_type',
        'head_id',
        'name',
        'is_visible_in_voyage_report',
        'is_readonly',
        'business_unit'
    ];

    /**
    * @var array
    */
    protected $skipForDeletionCheck = [''];
    
    /**
    * @var array
    */
    protected $features = [
    // 'relationName'           => 'Menu',
    ];
    
        
    public function opsHeads()
    {
        return $this->hasMany(OpsExpenseHead::class, 'id', 'head_id');
    }

    public function opsHead() {
        return $this->hasOne(OpsExpenseHead::class, 'id', 'head_id');
    }

    public function opsSubHeads()
    {
        return $this->hasMany(OpsExpenseHead::class, 'head_id', 'id');
    }
}
