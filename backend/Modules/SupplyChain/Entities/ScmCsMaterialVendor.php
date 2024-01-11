<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmCs;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Entities\ScmCsVendor;
use Modules\SupplyChain\Entities\ScmMaterial;
use Modules\SupplyChain\Entities\ScmCsMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmCsMaterialVendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_cs_id', 'scm_cs_vendor_id', 'scm_cs_material_id', 'scm_material_id', 'scm_pr_id', 'brand', 'unit', 'origin', 'model', 'stock_type', 'manufacturing_days', 'offered_price', 'negotiated_price', 'quantity', 'amount',
    ];

    public function scmCs()
    {
        return $this->belongsTo(ScmCs::class);
    }

    public function scmCsVendor()
    {
        return $this->belongsTo(ScmCsVendor::class);
    }

    public function scmCsMaterial()
    {
        return $this->belongsTo(ScmCsMaterial::class);
    }

    public function scmMaterial()
    {
        return $this->belongsTo(ScmMaterial::class, 'scm_material_id', 'id');
    }

    public function scmPr()
    {
        return $this->belongsTo(ScmPr::class);
    }
}
