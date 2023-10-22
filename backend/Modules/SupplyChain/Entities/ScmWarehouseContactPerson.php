<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWarehouseContactPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_warehouse_id', 'name', 'designation', 'phone', 'email', 'status', 'assign_date', 'end_date',
    ];
}
