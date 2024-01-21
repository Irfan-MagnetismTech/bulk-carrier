<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWcsVendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_vendor_id',
        'scm_wcs_id',
        'quotation_ref_no',
        'quotation_date',
        'attachment',
        'quotation_validity',
        'payment_mode',
        'credit_term',
        'vat',
        'ait',
        'currency',
        'security_money',
        'adjustment_policy',
        'is_selected',
        'terms_and_condition',
        'remarks',
    ];

    protected $casts = ['is_selected' => 'boolean'];
    
    public function scmWcsVendorServices(): HasMany
    {
        return $this->hasMany(ScmWcsVendorService::class)->latest();
    }

    public function scmVendor()
    {
        return $this->belongsTo(ScmVendor::class, 'scm_vendor_id', 'id');
    }

    public function scmWcs()
    {
        return $this->belongsTo(ScmWcs::class, 'scm_wcs_id', 'id');
    }
}
