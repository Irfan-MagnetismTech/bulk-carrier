<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Traits\GlobalSearchTrait;
use Modules\SupplyChain\Entities\ScmPr;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmCs extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'scm_pr_id',
        'ref_no',
        'scm_warehouse_id',
        'acc_cost_center_id',
        'effective_date',
        'expire_date',
        'special_instructions',
        'priority',
        'required_days',
        'purchase_center',
        'selection_ground',
        'auditor_remarks_date',
        'auditor_remarks',
        'status',
        'business_unit',
        'created_by',
        'is_foreign',
    ];


    public function scmPr()
    {
        return $this->belongsTo(ScmPr::class);
    }

    public function scmWarehouse()
    {
        return $this->belongsTo(ScmWarehouse::class);
    }   
}
