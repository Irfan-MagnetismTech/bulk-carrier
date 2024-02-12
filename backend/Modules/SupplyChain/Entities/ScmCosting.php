<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmPo;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Modules\SupplyChain\Entities\ScmCostingLine;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SupplyChain\Entities\ScmCostingAllocation;

class ScmCosting extends Model
{
    use HasFactory,GlobalSearchTrait;

    protected $fillable = [
        'scm_po_id',
        'purchase_center',
        'date',
        'total_allocateable',
        'scm_warehouse_id',
    ];


    public function scmCostingLines()
    {
        return $this->hasMany(ScmCostingLine::class);
    }

    public function scmCostingAllocations()
    {
        return $this->hasMany(ScmCostingAllocation::class);
    }

    public function scmPo()
    {
        return $this->belongsTo(ScmPo::class);
    }

    public function scmWarehouse()
    {
        return $this->belongsTo(ScmWarehouse::class);
    }
}
