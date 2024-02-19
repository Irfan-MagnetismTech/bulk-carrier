<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpsBulkNoonReportConsumption extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_bulk_noon_report_id',
        'ops_bunker_id',
        'scm_material_id',
        'type',
        'previous_rob',
        'received',
        'consumption',
        'rob',
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

    public function opsBulkNoonReportConsumptionHeads()
    {
        return $this->hasMany(OpsBulkNoonReportConsumptionHead::class, 'ops_bulk_noon_report_consumption_id' , 'id');
    }

    public function scmMaterial()
    {
        return $this->belongsTo(ScmMaterial::class, 'scm_material_id' , 'id');
    }
}
