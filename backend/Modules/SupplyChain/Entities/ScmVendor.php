<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\SupplyChain\Entities\ScmVendorContactPerson;

class ScmVendor extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'name', 'address', 'country_id', 'country_name', 'vendor_type', 'account_ref_no', 'product_source_type', 'product_type',
    ];

    public function scmVendorContactPersons(): HasMany
    {
        return $this->hasMany(ScmVendorContactPerson::class)->latest();
    }

    public function scmVendorContactPerson(): HasOne
    {
        return $this->hasOne(ScmVendorContactPerson::class);
    }
}
