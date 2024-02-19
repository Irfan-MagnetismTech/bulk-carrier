<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBulkNoonReportConsumptionHead extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_bulk_noon_report_id',
        'ops_bulk_noon_report_consumption_id',
        'type',
        'amount',
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

    public function scmMaterial()
    {
        return $this->belongsTo(ScmMaterial::class, 'scm_material_id' , 'id');
    }
}
