<?php

namespace Modules\Accounts\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccSalary extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['acc_cost_center_id','year_month','total_salary','remarks','business_unit'];

    public function accSalaryLines()
    {
        return $this->hasMany(AccSalaryLine::class);
    }

    public function costCenter()
    {
        return $this->belongsTo(AccCostCenter::class, 'acc_cost_center_id', 'id');
    }
}
