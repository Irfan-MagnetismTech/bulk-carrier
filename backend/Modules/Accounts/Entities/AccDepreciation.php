<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccDepreciation extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['month_year', 'applied_date', 'acc_cost_center_id', 'business_unit'];

    /**
     * @return mixed
     */
    public function accDepreciationLines()
    {
        return $this->hasMany(AccDepreciationLine::class);
    }

    public function costCenter()
    {
        return $this->belongsTo(AccCostCenter::class, 'acc_cost_center_id', 'id');
    }    
}
