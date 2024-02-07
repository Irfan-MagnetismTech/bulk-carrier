<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\SupplyChain\Entities\ScmVendorBillLine;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmVendorBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_no',
        'date',
    ];

    public function scmVendorBillLines(): HasMany
    {
        return $this->hasMany(ScmVendorBillLine::class);
    }
}
