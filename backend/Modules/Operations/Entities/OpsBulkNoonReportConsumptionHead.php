<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBulkNoonReportConsumptionHead extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_bulk_noon_report_consumption_id',
        'type',
        'amount',
    ];

    public function scmMaterial()
    {
        return $this->belongsTo(ScmMaterial::class, 'scm_material_id' , 'id');
    }
}
