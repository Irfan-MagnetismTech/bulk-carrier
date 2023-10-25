<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SupplyChain\Entities\ScmVendorContactPerson;

class ScmVendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'country_id', 'vendor_type', 'account_ref_no', 'product_source_type', 'product_type',
    ];

    public function scmVendorContactPersons(): HasMany
    {
        return $this->hasMany(ScmVendorContactPerson::class)->latest();
    }
}
