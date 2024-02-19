<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBulkNoonReportCargoTank extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_bulk_noon_report_id',
        'cargo_tanks',
        'liq_level',
        'pressure',
        'vapor_temp',
        'liq_temp',
        'quantity_mt',
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

    public function opsBulkNoonReport():BelongsTo
    {
        return $this->belongsTo(OpsBulkNoonReport::class, 'ops_bulk_noon_report_id' , 'id');
    }
}
