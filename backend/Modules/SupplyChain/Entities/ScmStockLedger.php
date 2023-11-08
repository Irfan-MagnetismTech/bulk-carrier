<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmStockLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_material_id', 'scm_warehouse_id', 'acc_cost_center_id', 'parent_id', 'stockable_type', 'stockable_id', 'recievable_type', 'recievable_id', 'quantity', 'gross_unit_price', 'gross_foreign_unit_price', 'net_unit_price', 'net_foreign_unit_price', 'currency', 'exchange_rate', 'business_unit', 'received_date',
    ];

    public function stockable()
    {
        return $this->morphTo();
    }

    public function parent()
    {
        return $this->belongsTo(ScmStockLedger::class, 'parent_id');
    }

    public function child()
    {
        return $this->hasMany(ScmStockLedger::class, 'parent_id');
    }
}
