<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmCs;
use Modules\SupplyChain\Entities\ScmVendor;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\SupplyChain\Entities\ScmCsMaterialVendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmCsVendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_cs_id',
        'scm_vendor_id',
        'quotation_ref',
        'quotation_date', //pi received date
        'quotation_attachment',
        'quotation_validity', //offer validity
        'payment_method', //payment condition
        'delivery_term',
        'sourcing',
        'date_of_rfq',
        'vendor_type',
        'quotation_shipment_date',
        'estimated_shipment', //days
        'port_of_shipment',
        'port_of_discharge',
        'currency',
        'mode_of_shipment',
        'terms_and_condition',
        'remarks',
        'credit_term',
        'carring_cost_bear_by',
        'unloading_cost_bear_by',
        'vat',
        'ait',
        'is_selected',
        'stock_type',
        'manufacturing_days',
        'quotations_received_date',
        'port_of_loading'
    ];

    protected $casts = ['is_selected' => 'boolean'];

    public function scmCs(): BelongsTo
    {
        return $this->belongsTo(ScmCs::class);
    }

    public function scmVendor(): BelongsTo
    {
        return $this->belongsTo(ScmVendor::class);
    }

    public function scmCsMaterialVendors(): HasMany
    {
        return $this->hasMany(ScmCsMaterialVendor::class);
    }

    // public function scmCsMaterials(): HasOne
    // {
    //     return $this->hasOne(ScmCsMaterial::class);
    // }

}
