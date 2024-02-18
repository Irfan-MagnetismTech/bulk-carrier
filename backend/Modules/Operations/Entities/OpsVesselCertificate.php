<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVesselCertificate extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'ops_vessel_id',
        'ops_maritime_certification_id',
        'issue_date',
        'expire_date',
        'attachment',
        'status',
        'reference_number',
        'business_unit',
        'created_by'
    ];

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class);
    }
    
    public function opsMaritimeCertification()
    {
        return $this->belongsTo(OpsMaritimeCertification::class);
    }

}
