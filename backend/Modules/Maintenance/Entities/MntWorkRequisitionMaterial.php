<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MntWorkRequisitionMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'mnt_work_requisition_id',
        'material_name',
        'specification',
        'unit',
        'quantity',
        'remarks'
    ];
}
