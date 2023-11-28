<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBulkNoonReportPort extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_bulk_noon_report_id',
        'last_port',
        'next_port',
        'eta',
        'distance_run',
        'dtg',
        'remarks',
    ];

    public function opsBulkNoonReport()
    {
        return $this->belongsTo(OpsBulkNoonReport::class, 'ops_bulk_noon_report_id' , 'id');
    }

    public function lastPort() {
        return $this->belongsTo(OpsPort::class, 'last_port', 'code');
    }

    public function nextPort() {
        return $this->belongsTo(OpsPort::class, 'next_port', 'code');
    }
}
