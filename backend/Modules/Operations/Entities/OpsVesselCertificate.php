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
        'ops_maritime_certificate_id',
        'issue_date',
        'expire_date',
        'attachment',
        'status',
        'reference_number',
        'created_by',
    ];

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }
    public function opsMaritimeCertification()
    {
        return $this->belongsTo(OpsMaritimeCertification::class, 'ops_maritime_certificate_id' , 'id');
    }

}
