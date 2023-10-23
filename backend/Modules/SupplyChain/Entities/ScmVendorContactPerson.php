<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmVendorContactPerson extends Model
{
    use HasFactory;

    protected $table = 'scm_vendor_contact_persons';

    protected $fillable = [
        'scm_vendor_id', 'name', 'designation', 'phone', 'email', 'status', 'assign_date', 'end_date',
    ];
}
