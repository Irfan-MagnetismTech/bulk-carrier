<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBulkNoonReportEngineInput extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_bulk_noon_report_id',
        'type',
        'engine_unit',
        'pco',
        'rack',
        'exh_temp',
        'business_unit'

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
}
