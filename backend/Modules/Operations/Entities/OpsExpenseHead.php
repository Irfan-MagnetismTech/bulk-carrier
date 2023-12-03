<?php

namespace Modules\Operations\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsExpenseHead extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'billing_type',
        'head_id',
        'name',
        'is_visible_in_voyage_report',
        'business_unit'

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
        return $this->hasMany(OpsExpenseHead::class, 'head_id', 'id')->with('heads');
    }
}
