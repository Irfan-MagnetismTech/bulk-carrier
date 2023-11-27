<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBulkNoonReportConsumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_bulk_noon_report_id',
        'ops_bunker_id',
        'scm_material_id',
        'type',
        'previous_rob',
        'received',
        'rob',
    ];

    public function opsBulkNoonReport()
    {
        return $this->belongsTo(OpsBulkNoonReport::class, 'ops_bulk_noon_report_id' , 'id');
    }

    public function opsBulkNoonReportConsumptionHeads()
    {
        return $this->hasMany(OpsBulkNoonReportConsumptionHead::class, 'ops_bulk_noon_report_consumption_id' , 'id');
    }
}
