<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBulkNoonReportPort extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_bulk_noon_report_id',
        'last_port',
        'next_port',
        'eta',
        'distance_run',
        'dtg',
        'remarks',
    ];

    /**
    * @var array
    */
    protected $skipForDeletionCheck = ['relationName'];
    
    /**
    * @var array
    */
    protected $features = [
    // 'relationName'           => 'Menu',
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
