<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVesselCertificate extends Model
{
    use HasFactory;

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
        'business_unit'
        'created_by',
    ];

    public function ops_vessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }
    public function ops_maritime_certification()
    {
        return $this->belongsTo(OpsMaritimeCertification::class, 'ops_maritime_certificate_id' , 'id');
    }

}
