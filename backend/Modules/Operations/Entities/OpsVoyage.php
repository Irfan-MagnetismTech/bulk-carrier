<?php

namespace Modules\Operations\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVoyage extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'ops_customer_id',
        'ops_vessel_id',
        'mother_vessel',
        'ops_cargo_type_id',
        'voyage_no',
        'voyage_sequence',
        'route',
        'load_port_distance',
        'sail_date',
        'transit_date',
        'remarks',
        'business_unit'
    ];

    public function opsCustomer()
    {
        return $this->belongsTo(OpsCustomer::class, 'ops_customer_id' , 'id');
    }

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    // public function opsMotherVessel()
    // {
    //     return $this->belongsTo(OpsVessel::class, 'mother_vessel_id' , 'id');
    // }

    public function opsCargoType()
    {
        return $this->belongsTo(OpsCargoType::class, 'ops_cargo_type_id' , 'id');
    }

    public function opsVoyageSectors()
    {
        return $this->hasMany(OpsVoyageSector::class);
    }

    public function opsVoyagePortSchedules()
    {
        return $this->hasMany(OpsVoyagePortSchedule::class);
    }

    public function opsBunkers()
    {
        return $this->morphMany(OpsBunker::class, 'bunkerable');
    }

    public function opsContractAssign() {
        return $this->hasMany(OpsContractAssign::class, 'ops_voyage_id','id');
    }

    public function opsChartererInvoices()
    {
        return $this->hasMany(OpsChartererInvoice::class, 'ops_charterer_profile_id', 'id');
    }
}
